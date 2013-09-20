<?php

/**
 * @file
 * Contains User.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObject;

/**
 * Entity bundle class for users.
 */
class User extends FacebookObject implements UserInterface {

  /**
   * Gets the bundle property definitions.
   */
  public static function getBundlePropertyInfo($entity_type, $entity_info, $bundle) {
    $properties['name'] = array(
      'label' => t("Full name"),
      'description' => t("The user's full name."),
      'type' => 'text',
      'getter callback' => 'entity_property_getter_method',
    );

    $properties['updated_time'] = array(
      'label' => t('Updated timestamp'),
      'description' => t("The last time the user's profile was updated."),
      'type' => 'date',
      'getter callback' => 'entity_property_getter_method',
    );

    return $properties;
  }

  /**
   * The last time the user's profile was updated.
   *
   * @var string
   */
  public $updated_time;

  /**
   * The user's full name.
   *
   * @var string
   */
  public $name;

  /**
   * {@inheritdoc}
   */
  public function getUpdatedTime() {
    if (!empty($this->updated_time)) {
      return strtotime($this->updated_time, REQUEST_TIME);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->name;
  }

}
