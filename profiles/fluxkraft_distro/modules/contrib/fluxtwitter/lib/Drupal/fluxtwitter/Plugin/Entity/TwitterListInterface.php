<?php

/**
 * @file
 * Contains TwitterListInterface.
 */

namespace Drupal\fluxtwitter\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Interfaces for List objects.
 */
interface TwitterListInterface extends RemoteEntityInterface {

  /**
   * Gets the owner of the list.
   *
   * @return string
   *   The owner of the list.
   */
  public function getUser();

  /**
   * Gets the owner of the list.
   *
   * @return string
   *   The owner of the list.
   */
  public function getName();

}
