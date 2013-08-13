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
   * Instantiates a new entity object based on a service response.
   *
   * @param array $values
   *   The property values of the entity (e.g. the response of the service).
   * @param string $entity_type
   *   The entity type to create.
   * @param $entity_info
   *   The info of the entity type.
   *
   * @return RemoteEntityInterface
   *   An instantiated entity object.
   */
  public static function factory(array $values, $entity_type, $entity_info);

  /**
   * Gets the service account associated with the entity.
   *
   * @return \Drupal\fluxservice\Plugin\Entity\Account|null
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
   * @return \Drupal\fluxservice\Plugin\Entity\Account|null
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
   * @return string
   *   The remote identifier.
   */
  public function getRemoteIdentifier();

}
