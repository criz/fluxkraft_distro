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
class TwitterTweetController extends RemoteEntityController {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $client = $agent->client();
    foreach ($ids as $id) {
      // We need to cast to (int) because of the strict type validation
      // implemented by Guzzle.
      if ($response = $client->getTweet(array('id' => (int) $id))) {
        $output[$id] = $response;
      }
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  protected function sendToService(RemoteEntityInterface $tweet) {
    return $tweet->getAccount()->client()->sendTweet(array('status' => $tweet->text));
  }

  /**
   * {@inheritdoc}
   */
  protected function preEntify(array &$items, FluxEntityInterface $agent) {
    foreach ($items as &$values) {
      if (!empty($values['user'])) {
        // Process the attached Twitter user entity.
        $values['user'] = fluxservice_bycatch($values['user'], 'fluxtwitter_user', $agent);
      }
    }
  }

}
