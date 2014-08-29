<?php

/**
 * @file
 * Contains FeedEntryController.
 */

namespace Drupal\fluxfeed;

use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\Entity\RemoteEntityControllerByService;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller class for feed entries.
 */
class FeedEntryController extends RemoteEntityControllerByService {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, ServiceInterface $service) {
    // Just load all latest entries - usually ids will be in there.
    $items = array();
    foreach ($service->read() as $entry) {
      $items[$entry->getId()]['entry'] = $entry;
    }
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  protected function sendToService(RemoteEntityInterface $entity) {
    throw new \Exception("The entity type {$this->entityType} does not support writing.");
  }

}
