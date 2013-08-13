<?php

/**
 * @file
 * Contains OAuthServiceInterface.
 */

namespace Drupal\fluxservice\Plugin;

use Drupal\fluxservice\Plugin\Entity\Service;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Service plugin implementation for Twitter.
 */
interface OAuthServiceInterface extends ServiceInterface {

  /**
   * Returns the consumer key for authenticating with the web service.
   *
   * @param Core\Entity\Service $service
   *   The service entity.
   *
   * @return string
   *   The consumer key.
   */
  public function getConsumerKey(Service $service);

  /**
   * Returns the consumer secret for authenticating with the web service.
   *
   * @param Core\Entity\Service $service
   *   The service entity.
   *
   * @return string
   *   The consumer secret.
   */
  public function getConsumerSecret(Service $service);

}
