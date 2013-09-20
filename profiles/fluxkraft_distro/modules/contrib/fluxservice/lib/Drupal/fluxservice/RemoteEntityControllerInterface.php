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
   * Converts a web service response into fully fledged entities.
   *
   * @param array $items
   *   The array of items to entify.
   * @param \Drupal\fluxservice\Entity\FluxEntityInterface $agent
   *   The agent used to load the items.
   *
   * @return \Drupal\fluxservice\Entity\RemoteEntityInterface[]
   *   The entified entities.
   */
  public function entify(array $items, FluxEntityInterface $agent);

  /**
   * Processes values that were by-catched while querying a different resource.
   *
   * Sometimes web service responses contain related objects as "by-catch". This
   * method ensures that they are processed and written to the static entity
   * cache, such that they are available for future calls without having to
   * query the web service again.
   *
   * @param array $items
   *   The array of items to by-catch.
   * @param \Drupal\fluxservice\Entity\FluxEntityInterface $agent
   *   The agent used to load the items.
   *
   * @return \Drupal\fluxservice\Entity\RemoteEntityInterface[]
   *   The by-catched entities.
   */
  public function bycatch(array $items, FluxEntityInterface $agent);

}
