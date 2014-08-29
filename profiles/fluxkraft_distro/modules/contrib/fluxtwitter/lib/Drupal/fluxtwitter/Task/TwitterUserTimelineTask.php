<?php

/**
 * @file
 * Contains TwitterUserTimelineTask.
 */

namespace Drupal\fluxtwitter\Task;

/**
 * Event dispatcher for the Twitter user timeline of a given user.
 */
class TwitterUserTimelineTask extends TwitterTweetsTaskBase {

  /**
   * {@inheritdoc}
   */
  protected function getTweets(array $arguments) {
    $account = $this->getAccount();
    $tweets = array();
    if ($response = $account->client()->getUserTimeline($arguments)) {
      $tweets = fluxservice_entify_multiple($response, 'fluxtwitter_tweet', $account);
    };
    // Twitter sends the Tweets in the wrong order.
    return array_reverse($tweets);
  }

  /**
   * {@inheritdoc}
   */
  protected function getRequestArguments() {
    $arguments = parent::getRequestArguments();
    return $arguments + array(
      'screen_name' => $this->task['data']['screen_name'],
    );
  }

}
