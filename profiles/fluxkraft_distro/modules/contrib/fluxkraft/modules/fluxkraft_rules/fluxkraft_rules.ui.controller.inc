<?php

/**
 * @file
 * Holds a rules ui controller customizations.
 */

/**
 * A custom fluxkraft rules UI controller. See fluxkraft_rules_ui().
 */
class FluxkraftRulesUIController extends RulesUIController {


  /**
   * Overridden to customize ..
   */
  public function overviewTable($conditions = array(), $options = array()) {
    $options += array(
      'hide status op' => FALSE,
      'show plugin' => TRUE,
      'show events' => isset($conditions['plugin']) && $conditions['plugin'] == 'reaction rule',
      'show execution op' => !(isset($conditions['plugin']) && $conditions['plugin'] == 'reaction rule'),
    );
    // By default show only configurations owned by rules.
    $conditions += array(
      'owner' => 'rules',
    );
    if (!empty($options['base path'])) {
      RulesPluginUI::$basePath = $options['base path'];
    }
    else if (!isset(RulesPluginUI::$basePath)) {
      // Default to the current path, only if no path has been set yet.
      RulesPluginUI::$basePath = current_path();
    }

    $entities = entity_load('rules_config', FALSE, $conditions);
    ksort($entities);

    // Prepare some variables used by overviewTableRow().
    $this->event_info = rules_fetch_data('event_info');
    $this->cache = rules_get_cache();

    $rows = array();
    foreach ($entities as $id => $entity) {
      if (user_access('bypass rules access') || $entity->access()) {
        $render = entity_view('rules_config', array($entity), 'fluxkraft_rules_teaser');
        $rows[] = array('data' => drupal_render($render), 'class' => array('fluxkraft-rules-overview--element'));
      }
    }

    if (empty($rows)) {
      $rows[] = array('data' => t('Welcome, there are no rules so far. ;)') . '<br /><br />' . t('Add or import a rule using the buttons above.<br /> But don’t forget to authenticate service accounts <a href="!url">here</a> before!', array('!url' => '/fluxkraft/service-accounts')));
    }

    $attributes = array();
    $attributes['class'][] = 'fluxkraft-rules-overview--list';

    // TODO: hide configs where access() is FALSE.

    $output = theme('item_list', array('items' => $rows, 'title' => NULL, 'type' => 'ul', 'attributes' => $attributes));

    return $output;
  }

  /**
   * Builds the content of a configuration, e.g. as rendering at teasers.
   */
  protected function buildRuleContent(RulesPlugin $config, &$content) {
    // Add in configuration content: events, conditions, actions.
    if ($config instanceof RulesTriggerableInterface) {
      $content['events'] = $this->buildEventOverview($config);
    }
    if ($config instanceof Rule) {
      $content['conditions'] = $this->buildConditionOverview($config->conditions());
      $content['actions'] = $this->buildActionOverview($config->actions());
    }
    elseif ($config instanceof RulesActionContainer) {
      $content['actions'] = $this->buildActionOverview($config);
    }
    elseif ($config instanceof RulesConditionContainer) {
      $content['conditions'] = $this->buildConditionOverview($config);
    }
    return $content;
  }

  /**
   * Builds the content of a configuration, e.g. as rendering at teasers.
   */
  protected function buildEventOverview(RulesTriggerableInterface $config) {
    $content = array();
    foreach ($config->events() as $event_name) {
      $handler = rules_get_event_handler($event_name, $config->getEventSettings($event_name));
      $item_info = $handler->getEventInfo();
      $content[$event_name] = array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array('fluxkraft-rules-event'),
        ),
        'icon' => array(
          '#theme' => 'fluxkraft_rules_category_icon',
          '#category' => isset($item_info['category']) ? $item_info['category'] : FALSE,
          '#group' => isset($item_info['group']) ? $item_info['group'] : FALSE,
          '#module' => $item_info['module'],
        ),
        'info' => array(
          '#theme' => 'fluxkraft_rules_category_info',
          '#content' => $handler->summary(),
          '#item_type' => 'event',
          '#category' => isset($item_info['category']) ? $item_info['category'] : FALSE,
          '#group' => isset($item_info['group']) ? $item_info['group'] : FALSE,
        ),
      );
    }
    return $content;
  }

  /**
   * Builds the content of a configuration, e.g. as rendering at teasers.
   */
  protected function buildActionOverview($iterator) {
    $content = array();
    foreach ($iterator as $key => $action) {
      $item_info = $action->info();

      $content[$key] = array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array('fluxkraft-rules-action'),
        ),
        'icon' => array(
          '#theme' => 'fluxkraft_rules_category_icon',
          '#category' => isset($item_info['category']) ? $item_info['category'] : FALSE,
          '#group' => isset($item_info['group']) ? $item_info['group'] : FALSE,
        ),
        'info' => array(
          '#theme' => 'fluxkraft_rules_category_info',
          '#content' => $action->buildContent(),
          '#item_type' => 'action',
          '#group' => isset($item_info['group']) ? $item_info['group'] : $item_info['category'],
        ),
      );
    }
    return $content;
  }

  /**
   * Builds the content of a configuration, e.g. as rendering at teasers.
   */
  protected function buildConditionOverview($iterator) {
    $content = array();
    foreach ($iterator as $key => $condition) {
      $item_info = $condition->info();

      $content[$key] = array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array('fluxkraft-rules-condition'),
        ),
        'icon' => array(
          '#theme' => 'fluxkraft_rules_category_icon',
          '#category' => isset($item_info['category']) ? $item_info['category'] : FALSE,
          '#group' => isset($item_info['group']) ? $item_info['group'] : FALSE,
        ),
        'info' => array(
          '#theme' => 'fluxkraft_rules_category_info',
          '#content' => $condition->buildContent(),
          '#item_type' => 'condition',
          '#group' => isset($item_info['group']) ? $item_info['group'] : $item_info['category'],
        ),
      );
    }
    return $content;
  }

  /**
   * Preprocesses the template variables for rules-config.tpl.php.
   */
  public function preProcessTemplateRulesConfigTeaser(RulesPlugin $config, &$variables) {
    $variables['operations'] = $this->generateOperations($config);
    $variables['operation_attributes']['class'][] = 'fluxkraft-rules-operation';

    if (!$config->hasStatus(ENTITY_FIXED)) {
      $variables['url'] = url(RulesPluginUI::path($config->name));
    }
    $variables['tags'] = array_map('check_plain', $config->tags);
    $variables['tag_attributes']['class'][] = 'fluxkraft-rules-tag';

    $variables['activation_status'] = $config->active ? t('Active') : t('Inactive');
    $variables['author'] = format_username(user_load($config->uid));
    $variables['status'] = theme('entity_status', array($config->status));

    $this->buildRuleContent($config, $variables['content']);
  }

  /**
   * Processes the template variables for rules-config.tpl.php.
   */
  public function processTemplateRulesConfigTeaser(RulesPlugin $config, &$variables) {
    $variables['operation_attributes'] = drupal_attributes($variables['operation_attributes']);
    $variables['tag_attributes'] = drupal_attributes($variables['tag_attributes']);
  }

  /**
   * Generates an array of operation links.
   */
  protected function generateOperations(RulesPlugin $config) {
    // Load operations depending on the options and the exportable status.
    if (!$config->hasStatus(ENTITY_FIXED)) {
      $ops[] =  l(t('edit'), RulesPluginUI::path($config->name), array('attributes' => array('class' => array('edit', 'action'))));
      if (module_exists('rules_i18n')) {
        $ops[] =  l(t('translate'), RulesPluginUI::path($config->name, 'translate'), array('attributes' => array('class' => array('translate', 'action'))));
      }
    }
    else {
      $ops[] = '';
      if (module_exists('rules_i18n')) {
        $ops[] = '';
      }
    }

    // Add either an enable or disable link.
    $text = $config->active ? t('disable') : t('enable');
    $active_class = $config->active ? 'disable' : 'enable';
    $link_path = RulesPluginUI::path($config->name, $active_class);
    $ops[] = $config->hasStatus(ENTITY_FIXED) ? '' : l($text, $link_path, array('attributes' => array('class' => array($active_class, 'action')), 'query' => drupal_get_destination()));

    $ops[] = l(t('clone'), RulesPluginUI::path($config->name, 'clone'), array('attributes' => array('class' => array('clone', 'action'))));

    if (!$config->hasStatus(ENTITY_IN_CODE) && !$config->hasStatus(ENTITY_FIXED)) {
      $ops[] = l(t('delete'), RulesPluginUI::path($config->name, 'delete'), array('attributes' => array('class' => array('delete', 'action')), 'query' => drupal_get_destination()));
    }
    elseif ($config->hasStatus(ENTITY_OVERRIDDEN) && !$config->hasStatus(ENTITY_FIXED)) {
      $ops[] = l(t('revert'), RulesPluginUI::path($config->name, 'revert'), array('attributes' => array('class' => array('revert', 'action')), 'query' => drupal_get_destination()));
    }
    else {
      $ops[] = '';
    }
    $ops[] = l(t('export'), RulesPluginUI::path($config->name, 'export'), array('attributes' => array('class' => array('export', 'action'))));
    return $ops;
  }
}
