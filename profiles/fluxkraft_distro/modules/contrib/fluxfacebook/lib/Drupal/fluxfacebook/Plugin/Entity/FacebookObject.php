<?php

/**
 * @file
 * Contains FacebookObject.
 */

namespace Drupal\fluxfacebook\Plugin\Entity;

use Drupal\fluxservice\Entity\PluginConfigEntityInterface;
use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Facebook objects.
 *
 * This class can not be instantiated directly as the Facebook entity type uses
 * per-bundle classes.
 */
abstract class FacebookObject extends RemoteEntity implements FacebookObjectInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxfacebook_object',
      'label' => t('Facebook: Object'),
      'service' => 'fluxfacebook',
      'controller class' => '\Drupal\fluxfacebook\FacebookObjectController',
      'label callback' => 'entity_class_label',
      'entity keys' => array(
        'id' => 'drupal_entity_id',
        'bundle' => 'type',
        'remote id' => 'id',
      ),
      'bundles' => array(
        'user' => array(
          'label' => t('User'),
          'bundle class' => '\Drupal\fluxfacebook\Objects\User',
        ),
        'status' => array(
          'label' => t('Status message'),
          'bundle class' => '\Drupal\fluxfacebook\Objects\StatusMessage',
        ),
        'photo' => array(
          'label' => t('Photo'),
          'bundle class' => '\Drupal\fluxfacebook\Objects\Photo',
        ),
        'event' => array(
          'label' => t('Event'),
          'bundle class' => '\Drupal\fluxfacebook\Objects\Event',
        ),
        'page' => array(
          'label' => t('Page'),
          'bundle class' => '\Drupal\fluxfacebook\Objects\Page',
        ),
        'group' => array(
          'label' => t('Group'),
          'bundle class' => '\Drupal\fluxfacebook\Objects\Group',
        ),
      ),
    );
  }

  /**
   * Gets the entity property definitions.
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {
    $properties['id'] = array(
      'label' => t('Remote identifier'),
      'description' => t('The unique remote identifier of the object.'),
      'type' => 'integer',
    );

    $properties['type'] = array(
      'label' => t('Object type'),
      'description' => t("The type of the object (e.g. 'status' for status messages)."),
      'type' => 'text',
    );

    return $properties;
  }

  /**
   * The object id (remote identifier).
   *
   * @var int
   */
  public $id;

  /**
   * The object type (also the entity bundle).
   *
   * @var string
   */
  public $type;

  /**
   * {@inheritdoc}
   */
  public static function factory(array $values, $entity_type, $entity_info) {
    if (empty($values[$entity_info['entity keys']['bundle']])) {
      throw new \EntityMalformedException('The bundle property is required.');
    }

    // Instantiate the entity using the bundle class.
    $bundle = $values[$entity_info['entity keys']['bundle']];
    $class = $entity_info['bundles'][$bundle]['bundle class'];
    return new $class($values, $entity_type, $entity_info, $bundle);
  }

}
