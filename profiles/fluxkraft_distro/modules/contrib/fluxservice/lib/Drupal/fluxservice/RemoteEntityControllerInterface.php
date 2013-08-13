<?php

/**
 * @file
 * Contains \Drupal\fluxservice\RemoteEntityControllerInterface.
 */

namespace Drupal\fluxservice;

use Drupal\fluxservice\Entity\FluxEntityInterface;

/**
 * Interfaces for remote entity controllers.
 */
interface RemoteEntityControllerInterface extends \EntityAPIControllerInterface {

  /**
   * Allows entities to be bycatched while querying a different resource.
   *
   * Bycatching entities during a request puts them into entity cache, such that
   * they are available for future calls without requiring another service
   * request.
   *
   * @param array $items
   *   The array of items to entify and bycatch.
   * @param \Drupal\fluxservice\Entity\FluxEntityInterface $agent
   *   The agent used to load the item.
   *
   * @return array
   *   The array of bycatched entities.
   */
  public function entifyBycatch(array $items, FluxEntityInterface $agent);

}
