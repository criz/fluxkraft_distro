<?php

/**
 * @file
 * Contains Event.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObject;

/**
 * Entity bundle class for events.
 */
class Event extends FacebookObject implements EventInterface {

  /**
   * Gets the bundle property definitions.
   */
  public static function getBundlePropertyInfo($entity_type, $entity_info, $bundle) {
    // @todo Implement.
    return array();
  }

}
