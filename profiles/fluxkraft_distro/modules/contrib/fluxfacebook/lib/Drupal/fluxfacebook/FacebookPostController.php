<?php

/**
 * @file
 * Contains FacebookPostController.
 */

namespace Drupal\fluxfacebook;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\RemoteEntityController;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller class for posts.
 */
class FacebookPostController extends RemoteEntityController {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $client = $agent->client();
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
    // @todo Implement.
  }

  /**
   * {@inheritdoc}
   */
  protected function preEntify(array &$items, FluxEntityInterface $agent) {
    // @todo Implement.
  }

}
