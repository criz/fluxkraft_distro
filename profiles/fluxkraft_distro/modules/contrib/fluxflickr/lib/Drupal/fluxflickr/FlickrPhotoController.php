<?php

/**
 * @file
 * Contains FlickrPhotoController.
 */

namespace Drupal\fluxflickr;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\RemoteEntityController;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Class RemoteEntityController
 */
class FlickrPhotoController extends RemoteEntityController {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    $output = array();
    $client = $agent->client();
    foreach ($ids as $id) {
      if ($response = $client->getPhoto(array('photo_id' => $id, "format" => "json"))) {
        $output[$id] = $response['photo'];
      }
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  protected function sendToService(RemoteEntityInterface $photo) {
    //return $photo->getAccount()->client()->sendPhoto(array('status' => $photo->text));
    // Not implmented;
    return array();
  }

}
