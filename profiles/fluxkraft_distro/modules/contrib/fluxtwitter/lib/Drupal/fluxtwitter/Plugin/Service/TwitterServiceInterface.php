<?php

/**
 * @file
 * Contains TwitterServiceInterface
 */

namespace Drupal\fluxtwitter\Plugin\Service;

use Drupal\fluxservice\Service\OAuthServiceInterface;

/**
 * Interface for Twitter services.
 */
interface TwitterServiceInterface extends OAuthServiceInterface {

  /**
   * Gets the update interval.
   *
   * @return int
   *   The update interval.
   */
  public function getPollingInterval();

  /**
   * Sets the update interval.
   *
   * @param int $interval
   *   The update interval.
   *
   * @return TwitterServiceInterface
   *   The called object for chaining.
   */
  public function setPollingInterval($interval);

}
