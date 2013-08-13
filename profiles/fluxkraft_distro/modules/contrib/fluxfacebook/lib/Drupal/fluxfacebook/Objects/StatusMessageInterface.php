<?php

/**
 * @file
 * Contains StatusMessage.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObjectInterface;

/**
 * Entity bundle class for status messages.
 */
interface StatusMessageInterface extends FacebookObjectInterface {

  /**
   * Gets the message text.
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

}
