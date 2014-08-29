<?php

/**
 * @file
 * Contains TwitterRetweetAction.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\Action;

use Drupal\fluxtwitter\Plugin\Service\TwitterAccountInterface;

/**
 * Send a tweet action.
 */
class TwitterRetweetAction extends TwitterActionBase {

  /**
   * Defines the action.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtwitter_retweet',
      'label' => t('Retweet'),
      'parameter' => array(
        'tweet' => array(
          'type' => 'fluxtwitter_tweet',
          'label' => t('Tweet'),
          'wrapped' => TRUE,
        ),
        'account' => static::getServiceParameterInfo(),
      ),
    );
  }

  /**
   * Executes the action.
   */
  public function execute(\EntityDrupalWrapper $tweet, TwitterAccountInterface $account) {
    $response = $account->client()->retweet(array(
      'id' => (int) $tweet->value()->getRemoteIdentifier(),
    ));
    // Update the variable with the entity containing data.
    $tweet->set(fluxservice_bycatch($response, 'fluxtwitter_tweet', $account));
  }
}
