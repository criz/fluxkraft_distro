<?php

/**
 * @file
 * Webhook userserializer XML plugin class.
 */

/**
 * Webhook unserializer plugin for XML.
 */
class Webhook_Plugins_Unserializer_Xml implements Webhook_Plugins_Unserializer_Interface {

  /**
   * {@inheritdoc}
   */
  public function unserialize($data) {
    try {
      $xml = new SimpleXMLElement($data);
      $json = json_encode($xml);
      return (object) array($xml->getName() => json_decode($json));
    }
    catch (Exception $e) {
      throw new Webhook_Plugins_Unserializer_InvalidDataException($e->getMessage());
    }
  }
}
