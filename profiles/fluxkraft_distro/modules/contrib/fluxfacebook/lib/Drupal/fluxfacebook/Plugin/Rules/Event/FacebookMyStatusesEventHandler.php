<?php

/**
 * @file
 * Contains FacebookMyStatusesEventHandler.
 */

namespace Drupal\fluxfacebook\Plugin\Rules\EventHandler;

/**
 * Event handler for polling for Status updates on a user's timeline.
 */
class FacebookMyStatusesEventHandler extends FacebookEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxfacebook_my_statuses',
      'label' => t('A new status messages was posted on my timeline'),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'message' => static::getStatusMessageVariableInfo(),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxfacebook\TaskHandler\FacebookStatusesTaskHandler';
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return $this->eventInfo['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'owner' => 'me',
    ) + parent::getDefaults();
  }

}
