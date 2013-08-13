<?php

/**
 * @file
 * Contains LinkedInService.
 */

namespace Drupal\fluxlinkedin\Plugin\Service;

use Drupal\fluxservice\Service\OAuthServiceBase;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Service plugin implementation for LinkedIn.
 */
class LinkedInService extends OAuthServiceBase implements LinkedInServiceInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxlinkedin',
      'label' => t('LinkedIn'),
      'description' => t('Provides LinkedIn integration for fluxkraft.'),
      'class' => '\Drupal\fluxlinkedin\Plugin\Service\LinkedInServiceHandler',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return array(
      'service_url' => 'https://api.linkedin.com',
    ) + parent::getDefaultSettings();
  }

}
