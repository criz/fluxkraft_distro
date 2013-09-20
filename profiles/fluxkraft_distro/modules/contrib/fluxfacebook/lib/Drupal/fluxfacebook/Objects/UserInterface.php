<?php

/**
 * @file
 * Contains UserInterface.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObjectInterface;

/**
 * Entity bundle interface for users.
 */
interface UserInterface extends FacebookObjectInterface {

  /**
   * Gets the user's full name.
   *
   * @return string
   *   The user's full name.
   */
  public function getName();

  /**
   * Gets the last time the user's profile was updated
   *
   * @return int
   *   The timestamp for when the user's profile was updated the last time.
   */
  public function getUpdatedTime();

}
