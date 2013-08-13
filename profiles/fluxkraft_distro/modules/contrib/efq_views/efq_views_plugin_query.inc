<?php

class efq_views_plugin_query extends views_plugin_query {
  /**
   * The EntityFieldQuery object used for the query.
   */
  var $query;

  /**
   * Entity type, if set.
   */
  var $entity_type;

  /**
   * Constructor; Create the basic query object and fill with default values.
   */
  function init($base_table, $base_field, $options) {
    parent::init($base_table, $base_field, $options);

    $this->query = new EntityFieldQuery;
    $this->tables = array();

    // An entity type (such as EntityFieldQuery: Node) was selected.
    // We have entity type passed in as base table, prefixed with 'efq_'
    $entity_type = preg_replace('/^efq_/', '', $base_table);
    $this->entity_type = $entity_type;
    $this->query->entityCondition('entity_type', $entity_type);
  }

  function option_definition() {
    $options = parent::option_definition();
    $options['field_language'] = array(
      'default' => '***CURRENT_LANGUAGE***',
    );
    $options['query_tags'] = array(
      'default' => array(),
    );
    return $options;
  }

  /**
   * Show field language settings if the entity type we are querying has
   * field translation enabled.
   * If we are querying multiple entity types, then the settings are shown
   * if at least one entity type has field translation enabled.
   */
  function options_form(&$form, &$form_state) {
    if (isset($this->entity_type)) {
      $entities = array();
      $entities[$this->entity_type] = entity_get_info($this->entity_type);
    }
    else {
      $entities = entity_get_info();
    }

    $has_translation_handlers = FALSE;
    foreach ($entities as $type => $entity_info) {
      if (!empty($entity_info['translation'])) {
        $has_translation_handlers = TRUE;
      }
    }

    if ($has_translation_handlers) {
      $languages = array(
        '***CURRENT_LANGUAGE***' => t("Current user's language"),
        '***DEFAULT_LANGUAGE***' => t("Default site language"),
        LANGUAGE_NONE => t('No language')
      );
      $languages = array_merge($languages, locale_language_list());

      $form['field_language'] = array(
        '#type' => 'select',
        '#title' => t('Field Language'),
        '#description' => t('All fields which support translations will be displayed in the selected language.'),
        '#options' => $languages,
        '#default_value' => $this->options['field_language'],
      );
    }

    $form['query_tags'] = array(
      '#type' => 'textfield',
      '#title' => t('Query Tags'),
      '#description' => t('If set, these tags will be appended to the query and can be used to identify the query in a module. This can be helpful for altering queries.'),
      '#default_value' => implode(', ', $this->options['query_tags']),
      '#element_validate' => array('views_element_validate_tags'),
    );

    // The function views_element_validate_tags() is defined here.
    form_load_include($form_state, 'inc', 'views', 'plugins/views_plugin_query_default');
  }

  /**
   * Special submit handling.
   */
  function options_submit(&$form, &$form_state) {
    $element = array('#parents' => array('query', 'options', 'query_tags'));
    $value = explode(',', drupal_array_get_nested_value($form_state['values'], $element['#parents']));
    $value = array_filter(array_map('trim', $value));
    form_set_value($element, $value, $form_state);
  }

  /**
   * Builds the necessary info to execute the query.
   */
  function build(&$view) {
    $view->init_pager($view);

    // Let the pager modify the query to add limits.
    $this->pager->query();

    $count_query = clone $this->query;
    $count_query->count(true);

    $view->build_info['efq_query'] = $this->query;
    $view->build_info['count_query'] = $count_query;
  }

  /**
   * This is used by the style row plugins for node view and comment view.
   */
  function add_field($base_table, $base_field) {
    return $base_field;
  }

  /**
   * This is used by the field field handler.
   */
  function ensure_table($table) {
    return $table;
  }

  /**
   * Executes the query and fills the associated view object with according
   * values.
   *
   * Values to set: $view->result, $view->total_rows, $view->execute_time,
   * $view->pager['current_page'].
   */
  function execute(&$view) {
    $query = $view->build_info['efq_query'];
    $count_query = $view->build_info['count_query'];
    $args = $view->build_info['query_args'];

    $query->addMetaData('view', $view);
    $count_query->addMetaData('view', $view);

    // Add the query tags.
    if (!empty($this->options['query_tags'])) {
      foreach ($this->options['query_tags'] as $tag) {
        $query->addTag($tag);
        $count_query->addTag($tag);
      }
    }

    $start = microtime(true);

    // if we are using the pager, calculate the total number of results
    if ($this->pager->use_pager()) {
      try {
        $this->pager->total_items = $count_query->execute();

        if (!empty($this->pager->options['offset'])) {
          $this->pager->total_items -= $this->pager->options['offset'];
        }

        $this->pager->update_page_info();
      }
      catch (Exception $e) {
        if (!empty($view->simpletest)) {
          throw($e);
        }
        // Show the full exception message in Views admin.
        if (!empty($view->live_preview)) {
          drupal_set_message($e->getMessage(), 'error');
        }
        else {
          vpr('Exception in @human_name[@view_name]: @message', array('@human_name' => $view->human_name, '@view_name' => $view->name, '@message' => $e->getMessage()));
        }
        return;
      }
    }

    // Let the pager set limit and offset.
    $this->pager->pre_execute($query, $args);

    if (!empty($this->limit) || !empty($this->offset)) {
      // We can't have an offset without a limit, so provide a very large limit instead.
      $limit  = intval(!empty($this->limit) ? $this->limit : 999999);
      $offset = intval(!empty($this->offset) ? $this->offset : 0);
      $query->range($offset, $limit);
    }

    $view->result = array();
    try {
      $results = $query->execute();

      foreach ($results as $entity_type => $ids) {
        // This is later used for field rendering
        foreach (entity_load($entity_type, array_keys($ids)) as $entity_id => $entity) {
          $entity->entity_id = $entity_id;
          $entity->entity_type = $entity_type;
          $view->result[] = $entity;
        }
      }

      $this->pager->post_execute($view->result);
      if ($this->pager->use_pager()) {
        $view->total_rows = $this->pager->get_total_items();
      }
    }
    catch (Exception $e) {
      // Show the full exception message in Views admin.
      if (!empty($view->live_preview)) {
        drupal_set_message($e->getMessage(), 'error');
      }
      else {
        vpr('Exception in @human_name[@view_name]: @message', array('@human_name' => $view->human_name, '@view_name' => $view->name, '@message' => $e->getMessage()));
      }
      return;
    }

    $view->execute_time = microtime(true) - $start;
  }

  function get_result_entities($results, $relationship = NULL) {
    $entity = reset($results);
    return array($entity->entity_type, $results);
  }
  function add_selector_orderby($selector, $order = 'ASC') {
    $views_data = views_fetch_data($this->base_table);
    $sort_data = $views_data[$selector]['sort'];
    switch ($sort_data['handler']) {
      case 'efq_views_handler_sort_entity':
        $this->query->entityOrderBy($selector, $order);
        break;
      case 'efq_views_handler_sort_property':
        $this->query->propertyOrderBy($selector, $order);
        break;
      case 'efq_views_handler_sort_field':
        $this->query->fieldOrderBy($sort_data['field_name'], $sort_data['field'], $order);
        break;
    }
  }
}
