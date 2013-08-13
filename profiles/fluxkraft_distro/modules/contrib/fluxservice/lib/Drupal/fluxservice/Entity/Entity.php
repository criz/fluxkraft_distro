<?php

/**
 * @file
 * Contains PluginEntity.
 */

namespace Drupal\fluxservice\Entity;

/**
 * Class for remote entity objects.
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

}
