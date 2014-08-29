<?php

/**
 * @file
 * Contains Photo.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObject;

/**
 * Entity bundle class for photos.
 */
class Photo extends FacebookObject implements PhotoInterface {

  /**
   * {@inheritdoc}
   */
  public static function getBundlePropertyInfo($entity_type, $entity_info, $bundle) {
    $properties['created_time'] = array(
      'label' => t('Created timestamp'),
      'description' => t('The time the photo was initially published.'),
      'type' => 'date',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $properties['updated_time'] = array(
      'label' => t('Updated timestamp'),
      'description' => t('The last time the photo or its caption was updated'),
      'type' => 'date',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    return $properties;
  }

  /**
   * The time when the status message was created.
   *
   * @var string
   */
  public $created_time;

  /**
   * The time when the status message was updated.
   *
   * @var string
   */
  public $updated_time;

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
