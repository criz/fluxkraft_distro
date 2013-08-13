<?php

/**
 * @file
 * Contains Account.
 */

namespace Drupal\fluxservice\Plugin\Entity;

use Drupal\fluxservice\Entity\FluxEntity;

/**
 * Entity class for personal service accounts.
 */
abstract class Account extends FluxEntity implements AccountInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    $info = array(
      'name' => 'fluxservice_account',
      'label' => t('Service account'),
      'controller class' => '\Drupal\fluxservice\AccountController',
      'metadata controller class' => '\Drupal\fluxservice\AccountMetadataController',
      'views controller class' => '\Drupal\fluxservice\AccountViewsController',
      'extra fields controller class' => 'EntityDefaultExtraFieldsController',
      'base table' => 'fluxservice_account',
      'exportable' => TRUE,
      'uri callback' => 'entity_class_uri',
      'label callback' => 'entity_class_label',
      'access callback' => 'fluxservice_account_access',
      'entity keys' => array(
        'id' => 'id',
        'name' => 'uuid',
        'uuid' => 'uuid',
        'status' => 'status',
        'module' => 'module',
        'label' => 'label',
        'bundle' => 'plugin',
      ),
    );

    // Register available plugins as bundle.
    foreach (fluxservice_get_account_plugin_info() as $plugin => $plugin_info) {
      $info['bundles'][$plugin] = array(
        'label' => $plugin_info['label'],
        'bundle class' => $plugin_info['class'],
      );
    }

    return $info;
  }

  /**
   * The user.uid of the user that owns the account or NULL if the account does
   * not belong to anyone (site configuration).
   *
   * @var int|null
   */
  public $uid;

  /**
   * The machine-readable name of the service instance.
   *
   * @var string
   */
  public $service;

  /**
   * The remote identifier.
   *
   * @var string
   */
  public $remote_id;

  /**
   * Constructs a Account object.
   */
  public function __construct(array $values = array()) {
    parent::__construct($values, 'fluxservice_account');
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    if (isset($this->uid)) {
      return entity_load_single('user', $this->uid);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(\stdClass $user = NULL) {
    $this->uid = $user ? $user->uid : NULL;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteIdentifier() {
    return $this->remote_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setRemoteIdentifier($identifier) {
    $this->remote_id = $identifier;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getService() {
    if (isset($this->service)) {
      return entity_load_single('fluxservice_service', $this->service);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setService(Service $service) {
    $this->service = $service->identifier();
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareAccount() {
    // Nothing to do by default.
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginInfo() {
    return fluxservice_get_account_plugin_info($this->plugin);
  }

}
