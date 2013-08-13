<?php

/**
 * @file
 * Contains XingUser.
 */

namespace Drupal\fluxxing\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Class for xing users.
 */
class XingUser extends RemoteEntity implements XingUserInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxxing_user',
      'label' => t('Xing: User'),
      'service' => 'fluxxing',
      'controller class' => '\Drupal\fluxxing\XingUserController',
      'label callback' => 'entity_class_label',
      'access callback' => 'fluxservice_account_access',
      'entity keys' => array(
        'id' => 'drupal_entity_id',
        'remote id' => 'id',
        'label' => 'last_name',
      ),
      'fluxservice_efq_driver' => array(
        'default' => '\Drupal\fluxxing\XingUserContactQueryDriver',
      ),
      'fieldable' => FALSE,
      'extra fields controller class' => 'EntityDefaultExtraFieldsController',
    );
  }

  /**
   * Gets the entity property definitions.
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {
    $info['id'] = array(
      'label' => t('Remote identifier'),
      'description' => t('The unique remote identifier of the user.'),
      'type' => 'integer',
    );

    $info['last_name'] = array(
      'label' => t('Last name'),
      'type' => 'text',
    );

    $info['first_name'] = array(
      'label' => t('First name'),
      'type' => 'text',
    );

    return $info;
  }


}
