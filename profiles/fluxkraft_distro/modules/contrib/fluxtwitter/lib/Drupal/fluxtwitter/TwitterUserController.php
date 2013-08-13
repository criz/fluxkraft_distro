<?php

/**
 * @file
 * Contains TwitterTweetController.
 */

namespace Drupal\fluxtwitter;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\RemoteEntityController;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Class RemoteEntityController
 */
class TwitterUserController extends RemoteEntityController {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $client = $agent->client();
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
    // @todo Throw exception.
  }

}
