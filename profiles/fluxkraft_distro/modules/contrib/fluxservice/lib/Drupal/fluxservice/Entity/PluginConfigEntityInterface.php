<?php

/**
 * @file
 * Contains PluginConfigEntityInterface.
 */

namespace Drupal\fluxservice\Entity;

/**
 * Interface for plugin config entities, holding fluxservice plugin config.
 */
interface PluginConfigEntityInterface extends EntityInterface {

  /**
   * Gets the name of the plugin's type.
   *
   * @return string
   *   The plugin type name.
   *
   * @see hook_fluxservice_plugin_type_info()
   */
  public function getPluginType();

  /**
   * Gets the name of the plugin.
   *
   * @return string
   *   The plugin's name.
   */
  public function getPluginName();

  /**
   * Returns the plugin's definition info array.
   *
   * @return
   *   The plugin definition array.
   *
   * @see fluxservice_get_plugin_info()
   */
  public function getPluginInfo();

  /**
   * Gets an array of default settings.
   *
   * @return array
   *   The default settings.
   */
  public function getDefaultSettings();

  /**
   * Sets a custom label.
   *
   * @return self
   */
  public function setLabel($label);

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

}
