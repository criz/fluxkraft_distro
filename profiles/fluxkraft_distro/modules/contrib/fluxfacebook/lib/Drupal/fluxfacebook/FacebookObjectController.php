<?php

/**
 * @file
 * Contains FacebookObjectController.
 */

namespace Drupal\fluxfacebook;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityControllerByAccount;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller class for status messages.
 */
class FacebookObjectController extends RemoteEntityControllerByAccount {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account) {
    $output = array();
    $client = $account->client();
    foreach ($ids as $id) {
      if ($response = $client->getObject(array('id' => (int) $id, 'metadata' => TRUE))) {
        $output[$id] = $response->toArray();
        $output[$id]['type'] = $output[$id]['metadata']['type'];
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
