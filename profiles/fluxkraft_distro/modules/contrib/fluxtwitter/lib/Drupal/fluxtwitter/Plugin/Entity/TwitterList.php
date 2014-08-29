<?php

/**
 * @file
 * Contains TwitterList.
 */

namespace Drupal\fluxtwitter\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Twitter Tweets.
 */
class TwitterList extends RemoteEntity implements TwitterListInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtwitter_list',
      'label' => t('Twitter: List'),
      'module' => 'fluxtwitter',
      'service' => 'fluxtwitter',
      'controller class' => '\Drupal\fluxtwitter\TwitterListController',
      'label callback' => 'entity_class_label',
      'entity keys' => array(
        'id' => 'drupal_entity_id',
        'remote id' => 'id',
      ),
    );
  }

  /**
   * Gets the entity property definitions.
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {
    $info['id'] = array(
      'label' => t('Remote identifier'),
      'description' => t('The unique remote identifier of the Tweet.'),
      'type' => 'integer',
    );

    $info['user'] = array(
      'label' => t('Owner'),
      'description' => t('The Twitter user who owns this list.'),
      'type' => 'fluxtwitter_user',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    return $info;
  }

  /**
   * The owner of the list.
   *
   * @var string
   */
  public $user;

  /**
   * The name of the list.
   *
   * @var string
   */
  public $name;

  /**
   * {@inheritdoc}
   */
  public function getUser() {
    return $this->user;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->name;
  }

}
