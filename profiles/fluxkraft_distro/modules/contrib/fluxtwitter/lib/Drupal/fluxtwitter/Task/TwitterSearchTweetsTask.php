<?php

/**
 * @file
 * Contains TwitterSearchTweetsTask.
 */

namespace Drupal\fluxtwitter\Task;
use Drupal\fluxtwitter\Plugin\Entity\TwitterTweet;

/**
 * Event dispatcher for Twitter searches.
 */
class TwitterSearchTweetsTask extends TwitterTweetsTaskBase {

  /**
   * {@inheritdoc}
   */
  protected function getRequestArguments() {
    $arguments = parent::getRequestArguments();
    return $arguments + array(
      'q' => $this->task['data']['search'],
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function invokeEvent(TwitterTweet $tweet) {
    rules_invoke_event($this->getEvent(), $this->getAccount(), $tweet, $this->task['data']['search']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getTweets(array $arguments) {
    $account = $this->getAccount();
    $tweets = array();
    if (($response = $account->client()->searchTweets($arguments)) && !empty($response['statuses'])) {
      $tweets = fluxservice_entify_multiple($response['statuses'], 'fluxtwitter_tweet', $account);
    };
    return array_reverse($tweets);
  }

}
