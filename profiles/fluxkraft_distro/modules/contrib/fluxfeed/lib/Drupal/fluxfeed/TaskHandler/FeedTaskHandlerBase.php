<?php

/**
 * @file
 * Contains FeedTaskHandlerBase.
 */

namespace Drupal\fluxfeed\TaskHandler;

use Drupal\fluxservice\Rules\TaskHandler\RepetitiveTaskHandlerBase;

/**
 * Base class for Twitter task handlers that dispatch Rules events.
 */
abstract class FeedTaskHandlerBase extends RepetitiveTaskHandlerBase {

  /**
   * Gets the configured event name to dispatch.
   */
  public function getEvent() {
    return $this->task['identifier'];
  }

  /**
   * Gets the configured Feed.
   *
   * @return \Drupal\fluxfeed\Plugin\Service\FeedServiceInterface
   */
  public function getFeed() {
    $feed = entity_create('fluxservice_service', array('plugin' => 'fluxfeed'))
      ->setFeedUrl($this->task['data']['feed_url'])
      ->setPollingInterval($this->task['data']['polling_interval']);

    return $feed;
  }

  /**
   * {@inheritdoc}
   */
  public function afterTaskQueued() {
    $feed = $this->getFeed();

    // Continuously reschedule the task.
    db_update('rules_scheduler')
      ->condition('tid', $this->task['tid'])
      ->fields(array('date' => $this->task['date'] + $feed->getPollingInterval()))
      ->execute();
  }

}
