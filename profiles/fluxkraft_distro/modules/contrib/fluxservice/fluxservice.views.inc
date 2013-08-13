<?php

/**
 * @file
 * Views integration for the fluxservice module.
 */

/**
 * Implements hook_views_data_alter().
 */
function fluxservice_views_data_alter(&$data) {
  if (module_exists('efq_views')) {
    foreach (entity_get_info() as $entity_type => $info) {
      if (!empty($info['fluxservice_efq_driver'])) {
        // Register the query driver for this base table - by default 'default'.
        $data['efq_' . $entity_type]['table']['base']['fluxservice_driver'] = 'default';
        $data['efq_' . $entity_type]['table']['base']['query class'] = 'fluxservice_efq_query';
      }
    }
  }
}

/**
 * Implements hook_views_plugins().
 */
function fluxservice_views_plugins() {
  if (module_exists('efq_views')) {
    $plugins = array(
      'query' => array(
        'fluxservice_efq_query' => array(
          'title' => t('Flux Services Integration EntityFieldQuery'),
          'help' => t('Uses EntityFieldQuery for querying remote entities.'),
          'handler' => '\Drupal\fluxservice\Query\EntityFieldQueryViewsQuery',
        ),
      ),
    );
    return $plugins;
  }
}
