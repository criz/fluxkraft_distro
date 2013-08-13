<?php

/**
 * @file
 * Contains FeedEntryInterface.
 */

namespace Drupal\fluxfeed\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Provides a common interface for all Facebook objects.
 */
interface FeedEntryInterface extends RemoteEntityInterface {

  /**
   * Gets the copyright entry
   *
   * @return string
   *   The feed's copyright entry.
   */
  public function getCopyright();

  /**
   * Gets the feed's creation date
   *
   * @return string|null
   */
  public function getDateCreated();

  /**
   * Gets the feed's modification date
   *
   * @return string
   *   The feed's modification date.
   */
  public function getDateModified();

  /**
   * Gets the feed's description.
   *
   * @return string
   *   The feed's description.
   */
  public function getDescription();

  /**
   * Gets the feed's ID.
   *
   * @return string
   */
  public function getId();

  /**
   * Gets the feed's language.
   *
   * @return string
   *   The feed's language.
   */
  public function getLanguage();

  /**
   * Gets a link to the HTML source.
   *
   * @return string
   *   The feed's link.
   */
  public function getLink();

  /**
   * Gets a link to the XML feed.
   *
   * @return string
   *   The feed's link.
   */
  public function getFeedLink();

  /**
   * Gets the feed's title.
   *
   * @return string
   *   The feed's title.
   */
  public function getTitle();

}
