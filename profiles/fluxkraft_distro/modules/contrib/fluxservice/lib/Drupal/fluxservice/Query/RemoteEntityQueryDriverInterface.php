<?php

/**
 * @file
 * Contains RemoteEntityQueryDriverInterface.
 */

namespace Drupal\fluxservice\Query;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Interface for EntityFieldQuery remote query drivers.
 *
 * Remote query drivers execute an EFQ via a certain remote service. Each query
 * driver is supposed to handle one remote service, whereas the default query
 * driver is used with the regular EFQ-views integration. For any further
 * drivers further EFQ-views base-tables have to be defined.
 */
interface RemoteEntityQueryDriverInterface {

  /**
   * Instantiates a new entity object based on a service response.
   *
   * @param string $entity_type
   *   The entity type to create.
   * @param $entity_info
   *   THe info of the entity type.
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   (optional) The service account used.
   *
   * @return RemoteEntityQueryDriverInterface
   *   An instantiated query driver object.
   */
  public static function factory($entity_type, $entity_info, AccountInterface $account = NULL);

  /**
   * Returns the service account plugin used by this driver.
   *
   * @return string|null
   *   The plugin name or NULL if no account plugin is used.
   */
  public function getAccountPlugin();

  /**
   * Gets the service account.
   *
   * @return \Drupal\fluxservice\Plugin\Entity\AccountInterface|null
   *   The account entity, or NULL if none is set.
   */
  public function getAccount();

  /**
   * Gets the service handler.
   *
   * @return ServiceInterface
   *   The service handler object.
   */
  public function getService();

  /**
   * Sets a service account.
   *
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   The service account to set.
   */
  public function setAccount(AccountInterface $account);

  /**
   * Sets the service handler to use.
   *
   * @param \Drupal\fluxservice\Plugin\Entity\ServiceInterface $service
   *   The service handler to use.
   */
  public function setService(ServiceInterface $service);

  /**
   * Executes the query.
   *
   * @param \EntityFieldQuery $query
   *   A entity field query object.
   *
   * @return
   *   As documented by \EntityFieldQuery::execute().
   *
   * @see \EntityFieldQuery::execute()
   */
  public function execute(\EntityFieldQuery $query);

}
