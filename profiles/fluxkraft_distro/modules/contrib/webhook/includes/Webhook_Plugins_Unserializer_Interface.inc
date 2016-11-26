<?php

/**
 * @file
 * Webhook Unserializer Interface file.
 */

/**
 * Webhook Unserializer Interface
 *
 * Unserializes serialized data structures.
 */
interface Webhook_Plugins_Unserializer_Interface {

  /**
   * Unserializes submitted data.
   *
   * @param string $data
   *  The encoded data
   *
   * @return object
   *   The unserialized data as a PHP object.
   *
   * @throws Webhook_Plugins_Unserializer_InvalidDataException
   */
  public function unserialize($data);
}
