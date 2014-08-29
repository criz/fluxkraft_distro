<?php

/**
 * @file
 * Contains TwitterSearchTweetsEvent.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\EventHandler;

/**
 * Event handler for Twitter searches.
 */
class TwitterSearchTweetsEvent extends TwitterEventBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtwitter_search_tweet',
      'label' => t('A new Tweet matches a search term'),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'tweet' => static::getTweetVariableInfo(),
        'search' => array(
          'label' => t('Search term'),
          'type' => 'text',
          'description' => t('The search term with which the Tweets have been looked up.'),
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxtwitter\Task\TwitterSearchTweetsTask';
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'search' => '',
    ) + parent::getDefaults();
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $settings = $this->getSettings();
    return t('A new Tweet matches the search term %search.', array('%search' => $settings['search']));
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $form = parent::buildForm($form_state);
    $settings = $this->getSettings();

    $form['search'] = array(
      '#type' => 'textfield',
      '#title' => t('Search term'),
      '#description' => t('The search term to look up tweets with.'),
      '#default_value' => $settings['search'],
    );

    return $form;
  }

}
