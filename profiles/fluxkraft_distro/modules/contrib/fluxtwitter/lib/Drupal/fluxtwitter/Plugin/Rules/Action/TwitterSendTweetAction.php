<?php

/**
 * @file
 * Contains TwitterSendTweetAction.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\Action;

use Drupal\fluxtwitter\Plugin\Service\TwitterAccountInterface;

/**
 * Send a tweet action.
 */
class TwitterSendTweetAction extends TwitterActionBase {

  /**
   * Defines the action.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtwitter_tweet',
      'label' => t('Send tweet'),
      'parameter' => array(
        'status' => array(
          'type' => 'text',
          'label' => t('Status message'),
        ),
        'account' => static::getServiceParameterInfo(),
      ),
      'provides' => array(
        'tweet_sent' => array('type' => 'fluxtwitter_tweet', 'label' => t('Tweet sent')),
      ),
    );
  }

  /**
   * Executes the action.
   */
  public function execute($status, TwitterAccountInterface $account) {
    $response = $account->client()->sendTweet(array(
      'status' => $status,
    ));

    return array(
      'tweet_sent' => fluxservice_bycatch((array) $response, 'fluxtwitter_tweet', $account),
    );
  }
}
