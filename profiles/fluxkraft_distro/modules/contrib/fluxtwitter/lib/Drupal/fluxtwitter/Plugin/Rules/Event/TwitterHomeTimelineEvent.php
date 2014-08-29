<?php

/**
 * @file
 * Contains TwitterHomeTimelineEvent.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\EventHandler;

/**
 * Event handler for tweets on the personal timeline.
 */
class TwitterHomeTimelineEvent extends TwitterEventBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtwitter_home_timeline_tweet',
      'label' => t('A new Tweet appears on your timeline'),
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
    return 'Drupal\fluxtwitter\Task\TwitterHomeTimelineTask';
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $settings = $this->getSettings();
    if ($settings['account'] && $account = entity_load_single('fluxservice_account', $settings['account'])) {
      return t('A new Tweet appears on the home timeline of %account.', array('%account' => "@{$account->label()}"));
    }
    return $this->eventInfo['label'];
  }

}
