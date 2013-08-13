<?php

/**
 * @file
 * Contains FacebookObjectController.
 */

namespace Drupal\fluxfacebook;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\RemoteEntityController;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller class for status messages.
 */
class FacebookObjectController extends RemoteEntityController {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $client = $agent->client();
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
    // @todo Implement.
  }

}
