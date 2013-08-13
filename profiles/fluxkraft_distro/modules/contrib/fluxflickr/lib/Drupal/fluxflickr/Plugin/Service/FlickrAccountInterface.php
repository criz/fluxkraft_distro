<?php

/**
 * @file
 * Contains FlickrAccountInterface
 */

namespace Drupal\fluxflickr\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountInterface;

/**
 * Interface for Flickr accounts.
 */
interface FlickrAccountInterface extends OAuthAccountInterface {

  /**
   * Gets the Flickr client object.
   *   The web service client for the Flickr API.
   */
  public function client();

}
