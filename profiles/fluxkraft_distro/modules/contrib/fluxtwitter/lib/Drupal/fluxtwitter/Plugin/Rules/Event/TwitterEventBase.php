<?php

/**
 * @file
 * Contains TwitterEventBase.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\EventHandler;

use Drupal\fluxservice\Rules\DataUI\AccountEntity;
use Drupal\fluxservice\Rules\DataUI\ServiceEntity;
use Drupal\fluxservice\Rules\EventHandler\CronEventHandlerBase;
use Drupal\fluxtwitter\Plugin\Rules\Action\TwitterActionBase;

/**
 * Cron-based base class for Twitter event handlers.
 */
abstract class TwitterEventBase extends CronEventHandlerBase {

  /**
   * Returns info-defaults for twitter plugin handlers.
   */
  public static function getInfoDefaults() {
    return TwitterActionBase::getInfoDefaults();
  }

  /**
   * Rules twitter integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxtwitter');
  }

  /**
   * Returns info for the provided twitter service account variable.
   */
  public static function getServiceVariableInfo() {
    return array(
      'type' => 'fluxservice_account',
      'bundle' => 'fluxtwitter',
      'label' => t('Twitter account'),
      'description' => t('The account used for authenticating with the Twitter API.'),
    );
  }

  /**
   * Returns info for the provided tweet variable.
   */
  public static function getTweetVariableInfo() {
    return array(
      'type' => 'fluxtwitter_tweet',
      'label' => t('Tweet'),
      'description' => t('The tweet that triggered the event.'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'account' => '',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $settings = $this->getSettings();

    $form['account'] = array(
      '#type' => 'select',
      '#title' => t('Account'),
      '#description' => t('The service account used for authenticating with the Twitter API.'),
      '#options' => AccountEntity::getOptions('fluxtwitter', $form_state['rules_config']),
      '#default_value' => $settings['account'],
      '#required' => TRUE,
      '#empty_value' => '',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getEventNameSuffix() {
    return drupal_hash_base64(serialize($this->getSettings()));
  }

}
