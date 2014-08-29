<?php

/**
 * @file
 * Contains TwitterMentionsTimelineEvent.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\EventHandler;

/**
 * Event handler for tweets on the personal mentions timeline.
 */
class TwitterMentionsTimelineEvent extends TwitterEventBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtwitter_mentions_timeline_tweet',
      'label' => t('A new Tweet appears on your mentions timeline'),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'tweet' => static::getTweetVariableInfo(),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxtwitter\Task\TwitterMentionsTimelineTask';
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $settings = $this->getSettings();
    if ($settings['account'] && $account = entity_load_single('fluxservice_account', $settings['account'])) {
      return t('A new Tweet appears on the mentions timeline of %account.', array('%account' => "@{$account->label()}"));
    }
    return $this->eventInfo['label'];
  }

}
