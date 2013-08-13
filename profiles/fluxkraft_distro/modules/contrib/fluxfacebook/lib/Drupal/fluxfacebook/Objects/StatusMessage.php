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
   * Gets the bundle property definitions.
   */
  public static function getBundlePropertyInfo($entity_type, $entity_info, $bundle) {
    $properties['message'] = array(
      'label' => t('Content'),
      'description' => t('The content of the status message.'),
      'type' => 'text',
      'required' => TRUE,
      'setter callback' => 'entity_property_verbatim_set',
    );

    return $properties;
  }

  /**
   * The message text.
   *
   * @var string
   */
  public $message = '';

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
  }

}
