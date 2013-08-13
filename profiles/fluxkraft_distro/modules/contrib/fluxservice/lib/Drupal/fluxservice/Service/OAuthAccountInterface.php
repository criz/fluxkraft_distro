<?php

/**
 * @file
 * Contains OAuthAccountInterface.
 */

namespace Drupal\fluxservice\Service;

use Drupal\fluxservice\Plugin\Entity\AccountInterface;

/**
 * Common interface for OAuth accounts.
 */
interface OAuthAccountInterface extends AccountInterface {

  /**
   * Retrieves the account object OAuth callback.
   *
   * @param string $key
   *   The key that identifies the OAuth process.
   * @param string $plugin
   *   The plugin name.
   *
   * @return OAuthAccountInterface
   *   The account that should be authorized.
   */
  public static function getAccountForOAuthCallback($key, $plugin);

  /**
   * Access check for the OAuth callback.
   *
   * @return bool
   *   TRUE if access was granted, FALSE otherwise.
   */
  public function accessOAuthCallback();

  /**
   * Processes the OAuth callback.
   */
  public function processOAuthCallback();

}
