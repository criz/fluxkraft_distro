<?php

/**
 * @file
 * Contains PluginEntity.
 */

namespace Drupal\fluxservice\Entity;

/**
 * Base class for entity objects.
 */
class Entity extends \Entity implements EntityInterface {

  /**
   * Boolean indicating whether the entity should be forced to be new.
   *
   * @var bool
   */
  public $is_new;

  /**
   * {@inheritdoc}
   */
  public static function factory(array $values, $entity_type, $entity_info) {
    return new static($values, $entity_type);
  }

  /**
   * {@inheritdoc}
   */
  public function isNew() {
    return !empty($this->is_new) || !$this->identifier();
  }

  /**
   * {@inheritdoc}
   */
  public function enforceIsNew($value = TRUE) {
    $this->is_new = $value;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public static function getBundlePropertyInfo($entity_type, $entity_info, $bundle) {
    return array();
  }

}
