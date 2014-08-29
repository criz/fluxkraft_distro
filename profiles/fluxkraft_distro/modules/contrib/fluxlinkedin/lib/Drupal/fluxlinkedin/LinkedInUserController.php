<?php

/**
 * @file
 * Contains LinkedInUserController.
 */

namespace Drupal\fluxlinkedin;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityControllerByAccount;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Controller for linkedin users.
 */
class LinkedInUserController extends RemoteEntityControllerByAccount {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account = NULL) {
    $output = array();
    $client = $account->client();
    foreach ($ids as $id) {
      $response = $client->getMemberById(array(
        'id' => $id,
        'format' => 'json',
        'fields' => array(
          'id',
          'first-name',
          'last-name',
          'headline',
          'picture-url',
        )
      ));
      if (isset($response['id'])) {
        $output[$id] = $response;
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
