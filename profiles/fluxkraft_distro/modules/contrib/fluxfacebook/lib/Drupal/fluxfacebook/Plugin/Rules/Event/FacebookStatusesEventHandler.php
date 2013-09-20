<?php

/**
 * @file
 * Contains FacebookStatusesEventHandler.
 */

namespace Drupal\fluxfacebook\Plugin\Rules\EventHandler;

/**
 * Event handler for polling for Status updates on a user's timeline.
 */
class FacebookStatusesEventHandler extends FacebookEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxfacebook_statuses',
      'label' => t("A new status messages appears on a user's timeline"),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'message' => static::getStatusMessageVariableInfo(),
        'user' => array(
          'type' => 'text',
          'label' => t('Timeline owner'),
          'description' => t('The Facebook user that belongs to the watched timeline.'),
        ),
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
    $settings = $this->getSettings();
    if ($settings['account'] && $account = entity_load_single('fluxservice_account', $settings['account'])) {
      return $this->eventInfo['label'] . ' ' . t('of %account', array('%account' => $account->label()));
    }
    return $this->eventInfo['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'user' => '',
    ) + parent::getDefaults();
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $settings = $this->getSettings();
    $form = parent::buildForm($form_state);

    $form['user'] = array(
      '#type' => 'textfield',
      '#title' => t('Timeline owner'),
      '#description' => t('The Facebook user that belongs to the watched timeline.'),
      '#default_value' => $settings['user'],
      '#required' => TRUE,
    );

    return $form;
  }
}
