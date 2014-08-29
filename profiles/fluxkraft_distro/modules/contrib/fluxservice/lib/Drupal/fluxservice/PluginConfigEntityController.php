<?php

/**
 * @file
 * Contains PluginConfigEntityController.
 */

namespace Drupal\fluxservice;

/**
 * Overrides the default storage controller.
 *
 * Overrides the default fetch mode and then properly constructs the entity
 * objects in attachLoad() instead. This is required due to a problem with how
 * unserialization is currently implemented in the base storage controller.
 *
 * Without this fix, we cannot safely set a fallback value for the data property
 * in the constructor.
 */
class PluginConfigEntityController extends \EntityAPIControllerExportable {

  /**
   * {@inheritdoc}
   */
  public function query($ids, $conditions, $revision_id = FALSE) {
    $result = parent::query($ids, $conditions, $revision_id);
    $result->setFetchMode(\PDO::FETCH_OBJ);
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  protected function attachLoad(&$queried_entities, $revision_id = FALSE) {
    foreach ($queried_entities as $key => $entity) {
      $class = $this->entityInfo['entity class'];
      $queried_entities[$key] = $class::factory((array) $entity, $this->entityType, $this->entityInfo);
    }
    parent::attachLoad($queried_entities, $revision_id);
  }

  /**
   * {@inheritdoc}
   */
  public function create(array $values = array()) {
    $class = $this->entityInfo['entity class'];
    $entity = $class::factory($values, $this->entityType, $this->entityInfo);
    // Apply default settings.
    $entity->data->mergeArray($entity->getDefaultSettings(), FALSE);
    return $entity;
  }

  /**
   * Overridden to care about the data property being serialized as array.
   *
   * Unfortunately there is no easy way to customize the serialized data without
   * this or overriding the big fat save method.
   */
  public function invoke($hook, $entity) {
    if ($hook == 'insert' || $hook == 'update') {
      $entity->data = new ArrayCollection($entity->data);
    }
    parent::invoke($hook, $entity);
    if ($hook == 'presave') {
      $entity->data = $entity->data->toArray();
    }
  }

}
