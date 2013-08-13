<?php

/**
 * @file
 * Contains LinkedInAccountInterface
 */

namespace Drupal\fluxlinkedin\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountInterface;

/**
 * Interface for LinkedIn accounts.
 */
interface LinkedInAccountInterface extends OAuthAccountInterface {

  /**
   * Gets the LinkedIn client object.
   *
   * @return \Guzzle\Service\Client
   *   The web service client for the LinkedIn API.
   */
  public function client();

}
