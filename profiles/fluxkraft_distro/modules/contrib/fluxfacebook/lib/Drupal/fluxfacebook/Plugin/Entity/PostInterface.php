<?php

/**
 * @file
 * Contains PostInterface.
 */

namespace Drupal\fluxfacebook\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity interface for posts.
 */
interface PostInterface extends RemoteEntityInterface {

  /**
   * Gets the created timestamp.
   *
   * @return int
   *   The timestamp for when the post was published or NULL if it hasn't
   *   been published yet.
   */
  public function getCreatedTime();

  /**
   * Gets the created timestamp.
   *
   * @return int
   *   The timestamp for when the post was last updated or NULL if it hasn't
   *   been published yet.
   */
  public function getUpdatedTime();

}
