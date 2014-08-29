<?php

/**
 * @file
 * Contains TwitterTweetController.
 */

namespace Drupal\fluxtwitter;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityControllerByAccount;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller for Twitter users.
 */
class TwitterUserController extends RemoteEntityControllerByAccount {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account = NULL) {
    $output = array();
    $client = $account->client();
    // While regularly the user_id is used as remote id, support using the
    // screen name for easy referencing of twitter-users by screen name also.
    $property = is_numeric(current($ids)) ? 'user_id' : 'screen_name';

    foreach (array_chunk($ids, 100) as $group) {
      if ($response = $client->getUsers(array($property => $group))) {
        foreach ($response as $item) {
          $output[$item['id']] = $item;
        }
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
