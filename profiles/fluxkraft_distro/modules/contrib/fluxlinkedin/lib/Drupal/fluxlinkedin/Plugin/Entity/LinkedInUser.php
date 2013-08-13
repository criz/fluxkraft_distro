<?php

/**
 * @file
 * Contains LinkedInUser.
 */

namespace Drupal\fluxlinkedin\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Class for linkedIn users.
 */
class LinkedInUser extends RemoteEntity {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxlinkedin_user',
      'label' => t('LinkedIn: User'),
      'service' => 'fluxlinkedin',
      'controller class' => '\Drupal\fluxlinkedin\LinkedInUserController',
      'label callback' => 'entity_class_label',
      'access callback' => 'fluxservice_account_access',
      'entity keys' => array(
        'id' => 'drupal_entity_id',
        'remote id' => 'id',
        'label' => 'lastName',
      ),
      'fluxservice_efq_driver' => array(
        'default' => '\Drupal\fluxlinkedin\LinkedInUserContactQueryDriver',
      ),
      'extra fields controller class' => 'EntityDefaultExtraFieldsController',
    );
  }

  /**
   * Gets the entity property definitions.
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {
    $properties['id'] = array(
      'label' => t('Remote identifier'),
      'description' => t('The unique remote identifier of the user.'),
      'type' => 'integer',
    );

    $properties['lastName'] = array(
      'label' => t('Last name'),
      'type' => 'text',
    );

    $properties['firstName'] = array(
      'label' => t('First name'),
      'type' => 'text',
    );

    $properties['headline'] = array(
      'label' => t('Profile headline'),
      'type' => 'text',
    );

    return $properties;
  }

}
