<?php

/**
 * @file
 * Contains Page.
 */

namespace Drupal\fluxfacebook\Objects;

use Drupal\fluxfacebook\Plugin\Entity\FacebookObject;

/**
 * Entity bundle class for pages.
 */
class Page extends FacebookObject implements PageInterface {

  /**
   * Gets the bundle property definitions.
   */
  public static function getBundlePropertyInfo($entity_type, $entity_info, $bundle) {
    // @todo Implement.
    return array();
  }

}
