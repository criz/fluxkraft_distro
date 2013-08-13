<?php

/**
 * @file
 * Contains FluxEntity.
 */

namespace Drupal\fluxservice\Entity;

use Drupal\fluxservice\ArrayCollection;

/**
 * Base class for fluxservice plugin entities.
 */
abstract class FluxEntity extends Entity implements FluxEntityInterface {

  /**
   * The primary internal identifier of the entity.
   *
   * @var int
   */
  public $id;

  /**
   * The universally unique identifier of the entity.
   *
   * @var string
   */
  public $uuid;

  /**
   * The human-readable name of the entity.
   *
   * @var string
   */
  public $label;

  /**
   * A collection of additional data (e.g. settings).
   *
   * @var \Drupal\fluxservice\ArrayCollection
   */
  public $data;

  /**
   * The plugin name (e.g. fluxtwitter).
   *
   * @var string
   */
  public $plugin;

  /**
   * The machine-readable name of the module that provides this entity or NULL
   * if it has been configured through the user interface.
   *
   * @var string|null
   */
  public $module;

  /**
   * The status of the entity.
   *
   * @var int
   */
  public $status;

  /**
   * {@inheritdoc}
   */
  public static function factory(array $values, $entity_type, $entity_info) {
    if (empty($values[$entity_info['entity keys']['bundle']])) {
      throw new \EntityMalformedException('The bundle property is required.');
    }

    // Instantiate the entity using the bundle class.
    $bundle = $values[$entity_info['entity keys']['bundle']];
    $class = $entity_info['bundles'][$bundle]['bundle class'];

    return new $class($values, $entity_type);
  }

  /**
   * {@inheritdoc}
   */
  public static function getBundlePropertyInfo() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values = array(), $entity_type = NULL) {
    parent::__construct($values, $entity_type);

    if (!isset($this->plugin)) {
      throw new \EntityMalformedException(t('Missing plugin property.'));
    }

    $this->data = new ArrayCollection((array) $this->data);
  }

  /**
   * {@inheritdoc}
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * {@inheritdoc}
   */
  public function setStatus($status) {
    $this->status = $status;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getModules() {
    return $this->module;
  }

  /**
   * {@inheritdoc}
   */
  public function setModule($module) {
    $this->module = $module;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function setLabel($label) {
    $this->label = $label;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    // No form by default.
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsFormValidate(array $form, array &$form_state) {
    // Nothing to do here by default.
  }

  /**
   * {@inheritdoc}
   */
  public function settingsFormSubmit(array $form, array &$form_state) {
    $parents = isset($form['#parents']) ? $form['#parents'] : array();
    $values = (array) drupal_array_get_nested_value($form_state['values'], $parents);
    $settings = array_intersect_key($values, $this->getDefaultSettings());

    // Write the submitted settings into the collection.
    $this->data->mergeArray($settings);
  }

  /**
   * {@inheritdoc}
   */
  public function buildDetails($view_mode = 'full', $langcode = NULL) {
    return array();
  }

}
