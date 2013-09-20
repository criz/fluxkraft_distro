<?php

/**
 * @file
 * Contains StatusMessageInterface.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObjectInterface;

/**
 * Entity bundle interface for status messages.
 */
interface StatusMessageInterface extends FacebookObjectInterface {

  /**
   * Gets the message text.
   *
   * @return string|null
   *   The message text or NULL if it is not set.
   */
  public function getMessage();

  /**
   * Sets the message text.
   *
   * @param string $message
   *   The message text to be set.
   *
   * @return StatusMessageInterface
   *   The called object for chaining.
   */
  public function setMessage($message);

  /**
   * Gets the created timestamp.
   *
   * @return int
   *   The timestamp for when the message was last updated or NULL if it hasn't
   *   been published yet.
   */
  public function getUpdatedTime();

}
