<?php

/**
 * @file
 * Contains TwitterHomeTimelineTaskHandler.
 */

namespace Drupal\fluxtwitter\TaskHandler;

/**
 * Event dispatcher for the Twitter home timeline of a given user.
 */
class TwitterHomeTimelineTaskHandler extends TwitterTaskHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function runTask() {
    // Assemble the request arguments.
    $arguments = array(
      'count' => 200,
    );

    // We store the remote identifier of the last Tweet that was processed so
    // that we can benefit from the 'since_id' query argument.
    $store = fluxservice_key_value('fluxtwitter.timeline.home');
    if ($since_id = $store->get($this->task['identifier'])) {
      $arguments['since_id'] = $since_id;
    }

    $account = $this->getAccount();

    if ($tweets = $account->client()->getHomeTimeline($arguments)) {
      // Twitter sends the tweets in the wrong order.
      $tweets = array_reverse($tweets);
      $tweets = fluxservice_entify_bycatch_multiple($tweets, 'fluxtwitter_tweet', $account);
      foreach ($tweets as $tweet) {
        rules_invoke_event($this->getEvent(), $account, $tweet);
      }

      // Store the remote identifier of the last Tweet that was processed.
      $last = end($tweets);
      $store->set($this->task['identifier'], $last->getRemoteIdentifier());
    }
  }

}
