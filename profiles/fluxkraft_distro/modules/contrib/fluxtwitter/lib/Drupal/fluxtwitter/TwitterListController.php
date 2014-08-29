<?php

/**
 * @file
 * Contains TwitterListController.
 */

namespace Drupal\fluxtwitter;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityControllerByAccount;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller for Twitter lists.
 */
class TwitterListController extends RemoteEntityControllerByAccount {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account) {
    $output = array();
    $client = $account->client();
    foreach ($ids as $id) {
      // We need to cast to (int) because of the strict type validation
      // implemented by Guzzle.
      if ($response = $client->getList(array('id' => (int) $id))) {
        $output[$id] = $response;
      }
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  protected function sendToService(RemoteEntityInterface $list) {
    throw new \Exception("The entity type {$this->entityType} does not support writing.");
  }

  /**
   * {@inheritdoc}
   */
  protected function preEntify(array &$items, ServiceInterface $service, AccountInterface $account = NULL) {
    foreach ($items as &$values) {
      if (!empty($values['user'])) {
        // Process the attached Twitter user entity.
        $values['user'] = fluxservice_bycatch($values['user'], 'fluxtwitter_user', $account);
      }
    }
  }

}
