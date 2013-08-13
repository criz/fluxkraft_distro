<?php

/**
 * @file
 * Contains OAuthServiceInterface.
 */

namespace Drupal\fluxservice\Service;

use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Common interface for OAuth services.
 */
interface OAuthServiceInterface extends ServiceInterface {

  /**
   * Returns the consumer key for authenticating with the web service.
   *
   * @return string
   *   The consumer key.
   */
  public function getConsumerKey();

  /**
   * Returns the consumer secret for authenticating with the web service.
   *
   * @return string
   *   The consumer secret.
   */
  public function getConsumerSecret();

}
