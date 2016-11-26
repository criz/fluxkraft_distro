<?php

/**
 * @file
 * Webhook userserializer URL encoded form data plugin class.
 */

/**
 * Webhook unserializer plugin for URL encoded form data.
 */
class Webhook_Plugins_Unserializer_UrlEncoded implements Webhook_Plugins_Unserializer_Interface {
  /**
   * {@inheritdoc}
   */
  public function unserialize($data) {
    $vars = array();
    parse_str($data, $vars);
    return (object) $vars;
  }
}
