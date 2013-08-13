<?php

/**
 * @file
 * Contains DropboxAccountInterface
 */

namespace Drupal\fluxdropbox\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountInterface;

/**
 * Interface for Dropbox accounts.
 */
interface DropboxAccountInterface extends OAuthAccountInterface {

  /**
   * Gets the Dropbox client object.
   * The web service client for the Dropbox API.
   *
   * @return \Dropbox\API
   */
  public function client();
}
