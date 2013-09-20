<?php

/**
 * @file
 * Contains PhotoInterface.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObjectInterface;

/**
 * Entity bundle interface for photos.
 */
interface PhotoInterface extends FacebookObjectInterface {

  /**
   * Gets the created timestamp.
   *
   * @return int
   *   The timestamp for when the photo was published or NULL if it hasn't
   *   been published yet.
   */
  public function getCreatedTime();

  /**
   * Gets the created timestamp.
   *
   * @return int
   *   The timestamp for when the photo was last updated or NULL if it hasn't
   *   been published yet.
   */
  public function getUpdatedTime();

}
