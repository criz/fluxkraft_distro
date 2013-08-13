<?php

/**
 * @file
 * Contains FacebookAccountInterface
 */

namespace Drupal\fluxfacebook\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountInterface;

/**
 * Interface for Facebook accounts.
 */
interface FacebookAccountInterface extends OAuthAccountInterface {

  /**
   * Gets the account's access token.
   *
   * @return string
   *   The access token of the account.
   */
  public function getAccessToken();

  /**
   * Gets the Facebook Graph API client.
   *
   * @return \Guzzle\Service\Client
   *   The web service client for the Graph API.
   */
  public function client();

}
