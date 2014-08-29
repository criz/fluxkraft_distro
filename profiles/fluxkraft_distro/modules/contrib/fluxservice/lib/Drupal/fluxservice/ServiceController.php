<?php

/**
 * @file
 * Contains AccountController.
 */

namespace Drupal\fluxservice;

use DatabaseTransaction;
use EntityFieldQuery;

/**
 * Controller class for personal service accounts.
 */
class ServiceController extends PluginConfigEntityController {

  /**
   * {@inheritdoc}
   */
  public function create(array $values = array()) {
    $entity = parent::create($values);
    // Default to the plugin label if no label is set.
    if (!isset($entity->label)) {
      $info = $entity->getPluginInfo();
      $entity->label = $info['label'];
    }
    return $entity;
  }

  /**
   * Constructs a ServiceController object.
   */
  public function __construct($entity_type) {
    parent::__construct($entity_type);
    $this->UuidKey = $this->entityInfo['entity keys']['uuid'];
  }

  /**
   * {@inheritdoc}
   */
  public function save($entity, \DatabaseTransaction $transaction = NULL) {
    // Generate a UUID if it doesn't exist yet.
    if (empty($entity->{$this->UuidKey})) {
      $entity->{$this->UuidKey} = uuid_generate();
    }
    return parent::save($entity, $transaction);
  }

  /**
   * {@inheritdoc}
   */
  public function delete($ids, DatabaseTransaction $transaction = NULL) {
    $query = new EntityFieldQuery();
    $results = $query->entityCondition('entity_type', 'fluxservice_account')
      ->propertyCondition('service', $ids)
      ->execute();

    // Delete all service accounts that are attached to this endpoint.
    if (!empty($results['fluxservice_account'])) {
      $controller = entity_get_controller('fluxservice_account');
      $controller->delete(array_keys($results['fluxservice_account']), $transaction);
    }

    parent::delete($ids, $transaction);
  }

}
