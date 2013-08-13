<?php

/**
 * @file
 * Contains RemoteEntityQueryDriverBase.
 */

namespace Drupal\fluxservice\Query;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * A base class for executing an EFQ via a remote service.
 */
abstract class RemoteEntityQueryDriverBase implements RemoteEntityQueryDriverInterface {

  /**
   * The service handler.
   *
   * @var \Drupal\fluxservice\Plugin\Entity\ServiceInterface
   */
  protected $serviceHandler;

  /**
   * The user account.
   *
   * @var \Drupal\fluxservice\Plugin\Entity\AccountInterface
   */
  protected $account;

  /**
   * @var string
   */
  protected $entityType;

  /**
   * The entity info array.
   *
   * @var array
   */
  protected $entityInfo;

  /**
   * {@inheritdoc}
   */
  public static function factory($entity_type, $entity_info, AccountInterface $account = NULL) {
    $service = fluxservice_service_plugin_handler($entity_info['service']);
    return new static($entity_type, entity_get_info($entity_type), $service, $account);
  }

  /**
   * Base constructor for RemoteEntityQueryDriverBase objects.
   */
  public function __construct($entity_type, array $entity_info, ServiceInterface $service, AccountInterface $account = NULL) {
    $this->entityType = $entity_type;
    $this->entityInfo = $entity_info;
    $this->setService($service);
    $this->setAccount($account);
  }

  /**
   * {@inheritdoc}
   */
  public function getAccount() {
    return $this->account;
  }

  /**
   * {@inheritdoc}
   */
  public function getService() {
    return $this->serviceHandler;
  }

  /**
   * {@inheritdoc}
   */
  public function setAccount(AccountInterface $account = NULL) {
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public function setService(ServiceInterface $service) {
    $this->serviceHandler = $service;
  }
}
