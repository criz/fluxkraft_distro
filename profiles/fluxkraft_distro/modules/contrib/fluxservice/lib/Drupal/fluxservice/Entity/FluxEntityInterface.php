<?php

/**
 * @file
 * Contains FluxEntityInterface.
 */

namespace Drupal\fluxservice\Entity;

/**
 * Extended entity interface for fluxservice plugin entities.
 */
interface FluxEntityInterface extends EntityInterface {

  /**
   * Instantiates a new entity object based.
   *
   * @param array $values
   *   The property values of the entity (e.g. the response of the service).
   * @param string $entity_type
   *   The entity type to create.
   * @param $entity_info
   *   The info of the entity type.
   *
   * @return FluxEntityInterface
   *   An instantiated entity object.
   */
  public static function factory(array $values, $entity_type, $entity_info);

  /**
   * @return mixed
   */
  public static function getBundlePropertyInfo();

  /**
   * Gets the raw label property for a flux entity.
   *
   * @return mixed
   */
  public function getLabel();

  /**
   * Sets the label property.
   *
   * @param string $label
   *   The label to be set.
   *
   * @return FluxEntityInterface
   *   The called object for chaining.
   */
  public function setLabel($label);

  /**
   * Gets an array of default settings.
   *
   * @return array
   *   The default settings.
   */
  public function getDefaultSettings();

  /**
   * Form callback for the service settings form.
   */
  public function settingsForm(array &$form_state);

  /**
   * Form validation callback for the service settings form.
   */
  public function settingsFormValidate(array $form, array &$form_statee);

  /**
   * Form submission callback for the service settings form.
   */
  public function settingsFormSubmit(array $form, array &$form_state);

  /**
   * Builds the content for the detail view of the account.
   *
   * @param string $view_mode
   *   (Optional) The entity view mode. Defaults to 'full'.
   * @param string|null $langcode
   *   (Optional) A language code to use for rendering. Defaults to the global
   *   content language of the current request.
   *
   * @return array
   *   A renderable array.
   */
  public function buildDetails($view_mode = 'full', $langcode = NULL);

  /**
   * Returns the plugin definition info array of the entity's plugin.
   *
   * @return
   *   The plugin definition array.
   *
   * @see fluxservice_get_plugin_info()
   */
  public function getPluginInfo();
}
