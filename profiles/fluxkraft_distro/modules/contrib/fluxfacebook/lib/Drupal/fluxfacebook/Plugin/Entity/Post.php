<?php

/**
 * @file
 * Contains Post.
 */

namespace Drupal\fluxfacebook\Plugin\Entity;

use Drupal\fluxservice\Entity\PluginConfigEntityInterface;
use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity bundle base class for posts.
 */
abstract class Post extends RemoteEntity implements PostInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxfacebook_post',
      'label' => t('Facebook: Post'),
      'service' => 'fluxfacebook',
      'controller class' => '\Drupal\fluxfacebook\FacebookPostController',
      'label callback' => 'entity_class_label',
      'entity keys' => array(
        'id' => 'drupal_entity_id',
        'bundle' => 'type',
        'remote id' => 'id',
      ),
      'bundles' => array(
      ),
    );
  }

  /**
   * {@inheritdoc}
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

    $properties['created_time'] = array(
      'label' => t('Created timestamp'),
      'description' => t('The time the post was initially published.'),
      'type' => 'date',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $properties['updated_time'] = array(
      'label' => t('Updated timestamp'),
      'description' => t('The last time the post was updated'),
      'type' => 'date',
      'getter callback' => 'fluxservice_entity_property_getter_method',
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
   * The time when the post was initially created.
   *
   * @var string
   */
  public $created_time;

  /**
   * The time when the post was last updated.
   *
   * @var string
   */
  public $updated_time;

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

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    if (!empty($this->created_time)) {
      return strtotime($this->created_time, REQUEST_TIME);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getUpdatedTime() {
    if (!empty($this->updated_time)) {
      return strtotime($this->updated_time, REQUEST_TIME);
    }
  }

}
