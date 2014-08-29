<?php

/**
 * @file
 * Contains RemoteEntity.
 */

namespace Drupal\fluxservice\Entity;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Class for remote entity objects.
 */
class RemoteEntity extends Entity implements RemoteEntityInterface {

  /**
   * The account associated with the entity.
   *
   * @var \Drupal\fluxservice\Plugin\Entity\AccountInterface
   */
  protected $_account;

  /**
   * The service associated with the entity.
   *
   * @var \Drupal\fluxservice\Plugin\Entity\ServiceInterface
   */
  protected $_service;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values = array(), $entity_type = NULL) {
    parent::__construct($values, $entity_type);

    // Write some commonly used properties for convenience.
    $this->remoteIdKey = $this->entityInfo['entity keys']['remote id'];
  }

  /**
   * {@inheritdoc}
   */
  public function getAccount() {
    if (isset($this->_account)) {
      return entity_load_single('fluxservice_account', $this->_account);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setAccount(AccountInterface $account) {
    $this->_account = $account->identifier();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getService() {
    if (isset($this->_service)) {
      return entity_load_single('fluxservice_service', $this->_service);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setService(ServiceInterface $service) {
    $this->_service = $service->identifier();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteIdentifier() {
    if (isset($this->{$this->remoteIdKey})) {
      return $this->{$this->remoteIdKey};
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyValues() {
    // Return all properties which have metadata by default.
    $property_info = entity_get_all_property_info($this->entityType);
    return array_intersect_key(get_object_vars($this), $property_info);
  }

}
