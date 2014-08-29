<?php

/**
 * @file
 * Contains RemoteEntityInterface.
 */

namespace Drupal\fluxservice\Entity;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Interface for remote entity objects.
 */
interface RemoteEntityInterface extends EntityInterface {

  /**
   * Gets the service account associated with the entity.
   *
   * @return \Drupal\fluxservice\Plugin\Entity\AccountInterface|null
   *   The account entity, or NULL if none is set.
   */
  public function getAccount();

  /**
   * Associates an account with the entity.
   *
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   The service account to set.
   */
  public function setAccount(AccountInterface $account);

  /**
   * Gets the service associated with the entity.
   *
   * @return \Drupal\fluxservice\Plugin\Entity\ServiceInterface|null
   *   The account entity, or NULL if none is set.
   */
  public function getService();

  /**
   * Associates a service with the entity.
   *
   * @param \Drupal\fluxservice\Plugin\Entity\ServiceInterface $service
   *   The service to set.
   */
  public function setService(ServiceInterface $service);

  /**
   * Gets the remote identifier of the entity.
   *
   * @return mixed
   *   The remote identifier.
   */
  public function getRemoteIdentifier();

  /**
   * Gets an array of property values for all defined properties.
   *
   * @return array
   *   An array of property values, keyed by property names.
   */
  public function getPropertyValues();

}
