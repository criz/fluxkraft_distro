<?php

/**
 * @file
 * Contains RemoteEntityControllerByAccount
 */

namespace Drupal\fluxservice\Entity;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Base remote entity controller class loading entities by service accounts.
 */
abstract class RemoteEntityControllerByAccount extends RemoteEntityControllerBase {

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
    if ($ids === FALSE) {
      // Loading all entities is not supported.
      return array();
    }
    // Decode the ids and group them by service accounts.
    $groups = array();
    foreach ($ids as $id) {
      list($account_id, $remote_id) = explode(':', $id, 2);
      $groups[$account_id][$remote_id] = $remote_id;
    }

    $entities = array();
    foreach ($groups as $account_id => $identifiers) {
      // Note that we cannot easily multiple-load here as we do not know which
      // ID (numeric vs UUID) has been passed. However, that's not an issue as
      // usually we load only by one account anyway.
      $account = entity_load_single('fluxservice_account', $account_id);
      $service = $account ? $account->getService() : NULL;
      // If th account or service to load with is not available, do nothing.
      if ($service) {
        $items = $this->loadFromService($identifiers, $service, $account);
        $entities += $this->entify($items, $service, $account);
      }
    }

    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function buildDrupalEntityId($remote_id, ServiceInterface $service, AccountInterface $account = NULL) {
    if (!isset($account)) {
      throw new \LogicException("The entity type {$this->entityType} requires the service account parameter.");
    }
    return "{$account->identifier()}:$remote_id";
  }

  /**
   * Loads remote items via the remote service.
   *
   * @param array $ids
   *   An array of remote ids.
   * @param \Drupal\fluxservice\Plugin\Entity\ServiceInterface $service
   *   The service endpoint used to load the entities.
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   The service account used to load the entities.
   *
   * @return array
   *   An array of loaded items, keyed by remote id. It's safe to include
   *   additional, i.e. not requested items, to bycatch them for later.
   *   Not (more) existing entries should have the value FALSE.
   *
   * @throws \Exception
   *   For any connection problems.
   */
  abstract protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account);

}
