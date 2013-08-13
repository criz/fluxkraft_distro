<?php

/**
 * @file
 * Contains Account.
 */

namespace Drupal\fluxservice\Plugin\Entity;

use Drupal\fluxservice\Entity\FluxEntity;

/**
 * Entity class for personal service accounts.
 */
abstract class Service extends FluxEntity implements ServiceInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    $info = array(
      'name' => 'fluxservice_service',
      'label' => t('Service endpoint'),
      'controller class' => '\Drupal\fluxservice\ServiceController',
      'metadata controller class' => '\Drupal\fluxservice\ServiceMetadataController',
      'views controller class' => '\Drupal\fluxservice\ServiceViewsController',
      'base table' => 'fluxservice_service',
      'exportable' => TRUE,
      'uri callback' => 'entity_class_uri',
      'label callback' => 'entity_class_label',
      'access callback' => 'fluxservice_service_access',
      'entity keys' => array(
        'id' => 'id',
        'name' => 'uuid',
        'uuid' => 'uuid',
        'status' => 'status',
        'module' => 'module',
        'label' => 'label',
        'bundle' => 'plugin',
      ),
    );

    foreach (fluxservice_get_service_plugin_info() as $plugin => $plugin_info) {
      $info['bundles'][$plugin] = array(
        'label' => $plugin_info['label'],
        'bundle class' => $plugin_info['class'],
      );
    }

    return $info;
  }

  /**
   * Constructs a Service object.
   */
  public function __construct(array $values = array()) {
    parent::__construct($values, 'fluxservice_service');
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginInfo() {
    return fluxservice_get_service_plugin_info($this->plugin);
  }
}
