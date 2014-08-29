<?php

/**
 * @file
 * Contains FacebookEventHandlerBase.
 */

namespace Drupal\fluxfacebook\Plugin\Rules\EventHandler;

use Drupal\fluxservice\Rules\DataUI\AccountEntity;
use Drupal\fluxservice\Rules\EventHandler\CronEventHandlerBase;
use Drupal\fluxfacebook\Rules\RulesPluginHandlerBase;

/**
 * Cron-based base class for Facebook event handlers.
 */
abstract class FacebookEventHandlerBase extends CronEventHandlerBase {

  /**
   * Returns info-defaults for facebook plugin handlers.
   */
  public static function getInfoDefaults() {
    return RulesPluginHandlerBase::getInfoDefaults();
  }

  /**
   * Rules facebook integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxfacebook');
  }

  /**
   * Returns info for the provided facebook service account variable.
   */
  public static function getServiceVariableInfo() {
    return array(
      'type' => 'fluxservice_account',
      'bundle' => 'fluxfacebook',
      'label' => t('Facebook account'),
      'description' => t('The account used for authenticating with the Facebook API.'),
    );
  }

  /**
   * Returns info for the provided tweet variable.
   */
  public static function getStatusMessageVariableInfo() {
    return array(
      'type' => 'fluxfacebook_object',
      'bundle' => 'status',
      'label' => t('Status message'),
      'description' => t('The status message that triggered the event.'),
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
      '#description' => t('The service account used for authenticating with the Facebook API.'),
      '#options' => AccountEntity::getOptions('fluxfacebook', $form_state['rules_config']),
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
