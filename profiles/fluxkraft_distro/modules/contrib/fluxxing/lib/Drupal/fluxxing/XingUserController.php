<?php

/**
 * @file
 * Contains XingUserController.
 */

namespace Drupal\fluxxing;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityControllerByAccount;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Controller for Xing users.
 */
class XingUserController extends RemoteEntityControllerByAccount {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account) {
    $output = array();
    $client = $account->client();
    foreach ($ids as $id) {
      $response = $client->getUser(array('id' => $id));
      $output[$id] = $response['users'][0];
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function sendToService(RemoteEntityInterface $entity) {
    throw new \Exception("The entity type {$this->entityType} does not support writing.");
  }

}
