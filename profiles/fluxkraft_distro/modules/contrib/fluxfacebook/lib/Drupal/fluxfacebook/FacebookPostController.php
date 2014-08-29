<?php

/**
 * @file
 * Contains FacebookPostController.
 */

namespace Drupal\fluxfacebook;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityControllerByAccount;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller class for posts.
 */
class FacebookPostController extends RemoteEntityControllerByAccount {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account) {
    $output = array();
    $client = $account->client();
    foreach ($ids as $id) {
      if ($response = $client->getObject(array('id' => (int) $id))) {
        $output[$id] = $response->toArray();
      }
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  protected function sendToService(RemoteEntityInterface $entity) {
    throw new \Exception("The entity type {$this->entityType} does not support writing.");
  }

}
