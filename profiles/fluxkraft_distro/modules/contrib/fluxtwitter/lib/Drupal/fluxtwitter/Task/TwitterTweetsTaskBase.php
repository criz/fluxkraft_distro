<?php

/**
 * @file
 * Contains TwitterTweetsTaskBase.
 */

namespace Drupal\fluxtwitter\Task;

use Drupal\fluxtwitter\Plugin\Entity\TwitterTweet;

/**
 * Common base class for Tweet task handlers.
 */
abstract class TwitterTweetsTaskBase extends TwitterTaskBase {

  /**
   * {@inheritdoc}
   */
  public function runTask() {
    $identifier = $this->task['identifier'];
    $store = fluxservice_key_value('fluxtwitter.tweets.since');
    $arguments = $this->getRequestArguments();
    if ($tweets = $this->getTweets($arguments)) {
      foreach ($tweets as $tweet) {
        $this->invokeEvent($tweet);
      }

      // Store the remote identifier of the last Tweet that was processed.
      $last = end($tweets);
      $store->set($identifier, $last->getRemoteIdentifier());
    }
    elseif (empty($arguments['since_id'])) {
      $store->set($identifier, FALSE);
    }
  }

  /**
   * Retrieves the request arguments based on the event configuration.
   *
   * @return array
   *   The request arguments.
   */
  protected function getRequestArguments() {
    $arguments = array('count' => 100);
    // We store the remote identifier of the last Tweet that was processed so
    // that we can benefit from the 'since_id' query argument.
    $store = fluxservice_key_value('fluxtwitter.tweets.since');
    if ($since_id = $store->get($this->task['identifier'])) {
      $arguments['since_id'] = $since_id;
    }
    // If it hasn't been set yet, it means that we are running this for thes
    // first time. In order to prevent flooding and processing of old Tweets we
    // limit the request to a single Tweet.
    elseif ($since_id === NULL) {
      $arguments['count'] = 1;
    }
    return $arguments;
  }

  /**
   * Invokes a rules event after a new Tweet was received.
   *
   * @param TwitterTweet $tweet
   *   The Tweet for which to invoke the event.
   */
  protected function invokeEvent(TwitterTweet $tweet) {
    rules_invoke_event($this->getEvent(), $this->getAccount(), $tweet);
  }

  /**
   * Retrieves an array.
   *
   * @param array $arguments
   *   The request arguments based on the event configuration.
   *
   * @return \Drupal\fluxtwitter\Plugin\Entity\TwitterTweet[]
   *   An array of Tweet entities.
   */
  abstract protected function getTweets(array $arguments);

}
