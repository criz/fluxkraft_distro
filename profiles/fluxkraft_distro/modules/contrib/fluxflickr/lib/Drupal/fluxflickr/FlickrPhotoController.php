<?php

/**
 * @file
 * Contains FlickrPhotoController.
 */

namespace Drupal\fluxflickr;

use Drupal\fluxservice\Entity\RemoteEntityControllerByAccount;
use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller for flickr photos.
 */
class FlickrPhotoController extends RemoteEntityControllerByAccount {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, ServiceInterface $service, AccountInterface $account) {
    $output = array();
    $client = $account->client();
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
    throw new \Exception("The entity type {$this->entityType} does not support writing.");
  }

  /**
   * {@inheritdoc}
   */
  protected function preEntify(array &$items, ServiceInterface $service, AccountInterface $account = NULL) {
    foreach ($items as &$values) {
      if (!empty($values['user'])) {
        // Process the attached Flickr user entity.
        $values['user'] = fluxservice_bycatch($values['user'], 'fluxflickr_user', $account);
      }
    }
  }

}
