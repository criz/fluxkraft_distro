<?php

/**
 * @file
 * Contains \Drupal\fluxservice\RemoteEntityControllerInterface.
 */

namespace Drupal\fluxservice\Entity;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Interfaces for remote entity controllers.
 */
interface RemoteEntityControllerInterface extends \EntityAPIControllerInterface {

  /**
   * Converts a web service response into fully fledged entities.
   *
   * @param array $items
   *   The array of items to entify.
   * @param \Drupal\fluxservice\Plugin\Entity\ServiceInterface $service
   *   The service endpoint used to load the entities.
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   (optional) The service account used to load the entities, if any.
   *
   * @return \Drupal\fluxservice\Entity\RemoteEntityInterface[]
   *   The entify-ed entities.
   */
  public function entify(array $items, ServiceInterface $service, AccountInterface $account = NULL);

  /**
   * Processes values that were by-catched while querying a different resource.
   *
   * Sometimes web service responses contain related objects as "by-catch". This
   * method ensures that they are entify-ed and written to the static entity
   * cache, such that they are available for future calls without having to
   * query the web service again.
   *
   * @param array $items
   *   The array of items to by-catch.
   * @param \Drupal\fluxservice\Plugin\Entity\ServiceInterface $service
   *   The service endpoint used to load the entities.
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   (optional) The service account used to load the entities, if any.
   *
   * @return \Drupal\fluxservice\Entity\RemoteEntityInterface[]
   *   The by-catched entities.
   */
  public function bycatch(array $items, ServiceInterface $service, AccountInterface $account = NULL);

  /**
   * Builds Drupal's entity identifier for the given remote identifier.
   *
   * Service and account parameters may be required depending on the entity
   * type.
   *
   * @param mixed $remote_id
   *   The entity's remote identifier.
   * @param \Drupal\fluxservice\Plugin\Entity\ServiceInterface $service
   *   The service endpoint used to load the entities.
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   (optional) The service account used to load the entities, if any.
   *
   * @return mixed
   *   The entity ID to use.
   */
  public function buildDrupalEntityId($remote_id, ServiceInterface $service, AccountInterface $account = NULL);

}
