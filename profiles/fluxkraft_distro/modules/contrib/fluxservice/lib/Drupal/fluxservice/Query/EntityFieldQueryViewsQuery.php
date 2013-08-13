<?php

/**
 * @file
 * Contains EntityFieldQueryViewsQuery.
 */

namespace Drupal\fluxservice\Query;

/**
 * Customizes the EFQ views query plugin for use with remote entities.
 */
class EntityFieldQueryViewsQuery extends \efq_views_plugin_query {

  /**
   * The remote query driver.
   *
   * @var RemoteEntityQueryDriverInterface
   */
  protected $driver;

  /**
   * Constructor; Create the basic query object and fill with default values.
   */
  function init($base_table, $base_field, $options) {
    parent::init($base_table, $base_field, $options);
    $data = views_fetch_data($base_table);
    // Specify which query driver to use.
    $this->query->metaData['fluxservice']['driver'] = $data['table']['base']['fluxservice_driver'];
  }

  /**
   * Returns an instance of the EFQ driver associated with this base table.
   *
   * Note that the driver used during execution will be automatically created,
   * see fluxservice_entity_query_alter()
   *
   * @return RemoteEntityQueryDriverInterface
   */
  function getEfqDriver() {
    if (!isset($this->driver)) {
      // Set the account object to use also.
      $this->query->metaData['fluxservice']['account'] = $this->options['account'];
      $this->driver = fluxservice_get_query_driver($this->entity_type, $this->query);
    }
    return $this->driver;
  }

  /**
   * {@inheritdoc}
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    if ($plugin_name =  $this->getEfqDriver()->getAccountPlugin()) {
      $form['account'] = array(
        '#type' => 'select',
        '#title' => t('Service account'),
        '#options' => fluxservice_account_options($plugin_name),
        '#description' => t('Select the account under which to perform the requests for this view.'),
        '#required' => TRUE,
      );
    }
    else {
      $form['account'] = array('#type' => 'value', '#value' => FALSE);
    }
  }

  /**
   * {@inheritdoc}
   */
  function option_definition() {
    $options = parent::option_definition();
    return $options + array(
      'account' => array('default' => FALSE)
    );
  }

  /**
   * Overridden to add the right driver.
   */
  function build(&$view) {
    // Set the account object to use also.
    $this->query->metaData['fluxservice']['account'] = $this->options['account'];
    parent::build($view);
  }
}
