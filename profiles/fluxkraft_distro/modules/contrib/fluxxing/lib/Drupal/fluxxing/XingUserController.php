<?php

/**
 * @file
 * Contains XingUserController.
 */

namespace Drupal\fluxxing;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\RemoteEntityController;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Controller for Xing users.
 */
class XingUserController extends RemoteEntityController {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $client = $agent->client();
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
    // @todo Throw exception.
  }

}
