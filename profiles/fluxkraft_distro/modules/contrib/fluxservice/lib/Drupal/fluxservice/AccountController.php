<?php

/**
 * @file
 * Contains AccountController.
 */

namespace Drupal\fluxservice;

/**
 * Controller class for personal service accounts.
 */
class AccountController extends FluxEntityController {

  /**
   * Constructs a AccountController object.
   */
  public function __construct($entity_type) {
    parent::__construct($entity_type);
    $this->UuidKey = $this->entityInfo['entity keys']['uuid'];
  }

  /**
   * {@inheritdoc}
   */
  public function save($entity, \DatabaseTransaction $transaction = NULL) {
    // Generate a UUID if it doesn't exist yet.
    if (empty($entity->{$this->UuidKey})) {
      $entity->{$this->UuidKey} = uuid_generate();
    }
    return parent::save($entity, $transaction);
  }

}
