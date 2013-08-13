<?php

/**
 * @file
 * Contains LinkedInAccountInterface
 */

namespace Drupal\fluxxing\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountInterface;

/**
 * Interface for Xing accounts.
 */
interface XingAccountInterface extends OAuthAccountInterface {

  /**
   * Gets the Xing client object.
   *
   * @return \Guzzle\Service\Client
   *   The web service client for the Xing API.
   */
  public function client();

}
