<?php

/**
 * @file
 * Example plugin class for unserializing github commit hook data.
 */

/**
 * Webhook unserializer plugin for Github post receive data.
 */
class Webhook_Example_Plugins_Unserializer_Github implements Webhook_Plugins_Unserializer_Interface {
  public function unserialize($data) {
    $json = webhook_load_unserializer('json');
    return $json->unserialize($data);
  }
}
