<?php

/**
 * @file
 * Webhook Processor Interface file.
 */

/**
 * Webhook Processor Interface
 *
 * Processes data submitted to the webhook.
 */
interface Webhook_Plugins_Processor_Interface {

  /**
   * Configuration form.
   *
   * Provides a configuration form for the plugin.
   *
   * This is reserved for future use, but isn't currently implemented.
   *
   * @return array
   *   A Form API form structure array
   */
  public function config_form();

  /**
   * Processes data.
   *
   * @param object $data
   *  The encoded data
   *
   * @return object
   *   The unserialized data as a PHP object.
   *
   * @throws Webhook_Plugins_Processor_ErrorException
   */
  public function process(stdClass $data);
}
