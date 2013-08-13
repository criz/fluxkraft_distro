<?php

/**
 * @file
 * Contains TwitterAccountInterface
 */

namespace Drupal\fluxtwitter\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountInterface;

/**
 * Interface for Facebook accounts.
 */
interface TwitterAccountInterface extends OAuthAccountInterface {

  /**
   * Gets the Twitter client object.
   *
   * @return \Guzzle\Service\Client
   *   The web service client for the Twitter API.
   */
  public function client();

}
