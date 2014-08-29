<?php

/**
 * @file
 * Contains StatusMessage.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObject;

/**
 * Entity bundle class for status messages.
 */
class StatusMessage extends FacebookObject implements StatusMessageInterface {

  /**
   * {@inheritdoc}
   */
  public static function getBundlePropertyInfo($entity_type, $entity_info, $bundle) {
    $properties['updated_time'] = array(
      'label' => t('Updated timestamp'),
      'description' => t('The timestamp the status message was updated.'),
      'type' => 'date',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $properties['message'] = array(
      'label' => t('Content'),
      'description' => t('The content of the status message.'),
      'type' => 'text',
      'required' => TRUE,
      'setter callback' => 'entity_property_setter_method',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    return $properties;
  }

  /**
   * The time when the status message was updated.
   *
   * @var string
   */
  public $updated_time;

  /**
   * The message text.
   *
   * @var string
   */
  public $message;

  /**
   * {@inheritdoc}
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * {@inheritdoc}
   */
  public function setMessage($message) {
    $this->message = $message;
    return $this;
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
