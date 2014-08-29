<?php

/**
 * @file
 * Contains RemoteEntityControllerByService
 */

namespace Drupal\fluxservice\Entity;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Base remote entity controller class loading entities by a single service.
 */
abstract class RemoteEntityControllerBySingleService extends RemoteEntityControllerBase {

  /**
   * Gets the service endpoint used to load all entities.
   *
   * @return \Drupal\fluxservice\Plugin\Entity\ServiceInterface
   *   The service to use for loading all entities.
   */
  abstract public function getService();

  /**
   * {@inheritdoc}
   *
   * Note that we do not support loading by conditions, so $conditions can be
   * safely ignored - just as revisions.
   *
   * @return
   *   The results may contain more entities as requested, as bycatched entities
   *   can be included.
   */
  public function query($ids, $conditions, $revision_id = FALSE) {
    $items = $ids !== FALSE ? $this->loadFromService($ids) : $this->loadAllFromService();
    // If th account or service to load with is not available, do nothing.
    if ($service = $this->getService()) {
      return $this->entify($items, $service);
    }
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function buildDrupalEntityId($remote_id, ServiceInterface $service, AccountInterface $account = NULL) {
    return $remote_id;
  }

  /**
   * Loads remote items via the remote service.
   *
   * @param array $ids
   *   An array of remote ids.
   *
   * @return array
   *   An array of loaded items, keyed by remote id. It's safe to include
   *   additional, i.e. not requested items, to bycatch them for later.
   *   Not (more) existing entries should have the value FALSE.
   *
   * @throws \Exception
   *   For any connection problems.
   */
  abstract protected function loadFromService($ids);

  /**
   * Loads all remote items via the remote service.
   *
   * @return array
   *   An array of loaded items, keyed by remote id.
   *
   * @throws \Exception
   *   For any connection problems.
   */
  protected function loadAllFromService() {
    // This is not implemented by default.
    return array();
  }

}
