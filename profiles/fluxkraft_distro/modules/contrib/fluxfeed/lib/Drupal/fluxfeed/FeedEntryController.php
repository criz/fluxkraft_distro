<?php

/**
 * @file
 * Contains FeedEntryController.
 */

namespace Drupal\fluxfeed;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\RemoteEntityController;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Entity controller class for feed entries.
 */
class FeedEntryController extends RemoteEntityController {

  /**
   * {@inheritdoc}
   */
  protected function loadFromService($ids, FluxEntityInterface $agent) {
    return $agent->read();
  }

  /**
   * {@inheritdoc}
   */
  protected function sendToService(RemoteEntityInterface $entity) {
    // Unsupported.
  }

  /**
   * {@inheritdoc}
   */
  public function query($ids, $conditions, $revision_id = FALSE) {
    $entities = parent::query($ids,$conditions,$revision_id);
    return array_diff_key($entities, array_flip($ids));
  }

}
