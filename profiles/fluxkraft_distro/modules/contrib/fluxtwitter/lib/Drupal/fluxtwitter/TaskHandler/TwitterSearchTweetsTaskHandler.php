<?php

/**
 * @file
 * Contains TwitterSearchTweetsTaskHandler.
 */

namespace Drupal\fluxtwitter\TaskHandler;

/**
 * Event dispatcher for Twitter searches.
 */
class TwitterSearchTweetsTaskHandler extends TwitterTaskHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function runTask() {
    // Assemble the request arguments.
    $arguments = array(
      'q' => $this->task['data']['search'],
      'count' => 100,
    );

    // We store the remote identifier of the last Tweet that was processed so
    // that we can benefit from the 'since_id' query argument.
    $store = fluxservice_key_value('fluxtwitter.search');
    if ($since_id = $store->get($this->task['identifier'])) {
      $arguments['since_id'] = $since_id;
    }

    $account = $this->getAccount();

    if (($response = $account->client()->searchTweets($arguments)) && $tweets = $response['statuses']) {
      // Twitter sends the tweets in the wrong order.
      $tweets = array_reverse($tweets);
      $tweets = fluxservice_entify_bycatch_multiple($tweets, 'fluxtwitter_tweet', $account);
      foreach ($tweets as $tweet) {
        rules_invoke_event($this->getEvent(), $account, $tweet, $this->task['data']['search']);
      }

      // Store the remote identifier of the last Tweet that was processed.
      $last = end($tweets);
      $store->set($this->task['identifier'], $last->getRemoteIdentifier());
    }
  }

}
