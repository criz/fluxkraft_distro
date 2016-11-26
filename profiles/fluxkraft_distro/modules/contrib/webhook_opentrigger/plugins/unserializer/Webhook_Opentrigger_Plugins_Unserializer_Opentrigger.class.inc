<?php

/**
 * @file
 * Plugin class for unserializing incoming data by OpenTrigger.
 */

/**
 * Webhook unserializer plugin for unserializing incoming data by OpenTrigger.
 */
class Webhook_Opentrigger_Plugins_Unserializer_Opentrigger implements Webhook_Plugins_Unserializer_Interface {
  public function unserialize($data) {
    $json = webhook_load_unserializer('json');
    return $json->unserialize($data);
  }
}
