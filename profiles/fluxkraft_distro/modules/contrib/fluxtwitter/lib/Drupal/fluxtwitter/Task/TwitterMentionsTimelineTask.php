<?php

/**
 * @file
 * Contains TwitterMentionsTimelineTask.
 */

namespace Drupal\fluxtwitter\Task;

/**
 * Event dispatcher for the Twitter mentions timeline of a given user.
 */
class TwitterMentionsTimelineTask extends TwitterTweetsTaskBase {

  /**
   * {@inheritdoc}
   */
  protected function getTweets(array $arguments) {
    $account = $this->getAccount();
    $tweets = array();
    if ($response = $account->client()->getMentionsTimeline($arguments)) {
      $tweets = fluxservice_entify_multiple($response, 'fluxtwitter_tweet', $account);
    };
    // Twitter sends the Tweets in the wrong order.
    return array_reverse($tweets);
  }

}
