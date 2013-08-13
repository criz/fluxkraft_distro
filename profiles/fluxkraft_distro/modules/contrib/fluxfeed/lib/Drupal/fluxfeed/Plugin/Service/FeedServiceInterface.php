<?php

/**
 * @file
 * Contains FeedServiceInterface
 */

namespace Drupal\fluxfeed\Plugin\Service;

use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Interface for the Feed service.
 */
interface FeedServiceInterface extends ServiceInterface {

  /**
   * Fetches and parses the configured feed URL.
   *
   * @return \Zend\Feed\Reader\Feed\FeedInterface
   *   The parsed feed.
   */
  public function read();

  /**
   * Gets the configured feed URL.
   *
   * @return string
   *   The feed URL.
   */
  public function getFeedUrl();

  /**
   * Sets the feed URL.
   *
   * @param string $url
   *   The URL of the feed.
   *
   * @return FeedServiceInterface
   *   The called object for chaining.
   */
  public function setFeedUrl($url);

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
   * @return FeedServiceInterface
   *   The called object for chaining.
   */
  public function setPollingInterval($interval);

}
