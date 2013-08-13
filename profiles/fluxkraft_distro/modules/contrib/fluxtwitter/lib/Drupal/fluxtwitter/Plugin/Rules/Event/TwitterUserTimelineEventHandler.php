<?php

/**
 * @file
 * Contains TwitterUserTimelineEventHandler.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\EventHandler;

/**
 * Event handler for tweets on user timelines.
 */
class TwitterUserTimelineEventHandler extends TwitterEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtwitter_user_timeline_tweet',
      'label' => t('A new tweet appears on the user timeline'),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'tweet' => static::getTweetVariableInfo(),
        'user' => array(
          'type' => 'fluxtwitter_user',
          'label' => t('Timeline owner'),
          'description' => t('The Twitter user that belongs to the watched timeline.'),
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxtwitter\TaskHandler\TwitterUserTimelineTaskHandler';
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'screen_name' => '',
    ) + parent::getDefaults();
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $settings = $this->getSettings();
    return $this->eventInfo['label'] . ' ' . t('of %user', array('%user' => "@{$settings['screen_name']}"));
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $form = parent::buildForm($form_state);
    $settings = $this->getSettings();

    $form['screen_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Twitter user'),
      '#description' => t('The Twitter user whose timeline should be watched.'),
      '#default_value' => $settings['screen_name'],
      '#required' => TRUE,
    );

    return $form;
  }

}
