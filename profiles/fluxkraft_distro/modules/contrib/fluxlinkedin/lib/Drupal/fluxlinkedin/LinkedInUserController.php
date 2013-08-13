<?php

/**
 * @file
 * Contains LinkedInUserController.
 */

namespace Drupal\fluxlinkedin;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\RemoteEntityController;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Controller for linkedin users.
 */
class LinkedInUserController extends RemoteEntityController {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $client = $agent->getClient();
    foreach ($ids as $id) {
      $response = $client->getMemberById(array('id' => $id));
      $output[$id] = $response['values'][0];
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
