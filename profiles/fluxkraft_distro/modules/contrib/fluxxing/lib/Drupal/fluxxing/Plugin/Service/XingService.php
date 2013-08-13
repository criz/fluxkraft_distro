<?php

/**
 * @file
 * Contains XingService.
 */

namespace Drupal\fluxxing\Plugin\Service;

use Drupal\fluxservice\Service\OAuthServiceBase;

/**
 * Service plugin implementation for Xing.
 */
class XingService extends OAuthServiceBase implements XingServiceInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxxing',
      'label' => t('Xing'),
      'description' => t('Provides Xing integration for fluxkraft.'),
      'class' => '\Drupal\fluxxing\Plugin\Service\XingServiceHandler',
    );
  }

}
