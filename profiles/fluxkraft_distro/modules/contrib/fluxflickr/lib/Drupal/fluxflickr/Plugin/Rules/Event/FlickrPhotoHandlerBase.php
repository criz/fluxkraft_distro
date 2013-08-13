<?php

/**
 * @file
 * Contains FlickrEventHandlerBase.
 */

namespace Drupal\fluxflickr\Plugin\Rules\EventHandler;

use Drupal\fluxservice\Rules\DataUI\AccountEntity;
use Drupal\fluxservice\Rules\DataUI\ServiceEntity;
use Drupal\fluxservice\Rules\EventHandler\CronEventHandlerBase;
use Drupal\fluxflickr\Rules\RulesPluginHandlerBase;

/**
 * Cron-based base class for Flickr event handlers.
 */
abstract class FlickrEventHandlerBase extends CronEventHandlerBase {

  /**
   * Returns info-defaults for flickr plugin handlers.
   */
  public static function getInfoDefaults() {
    return RulesPluginHandlerBase::getInfoDefaults();
  }

  /**
   * Returns info for the provided flickr service account variable.
   */
  public static function getServiceVariableInfo() {
    return array(
      'type' => 'fluxservice_account',
      'bundle' => 'fluxflickr',
      'label' => t('Flickr account'),
      'description' => t('The account used for authenticating with the Flickr API.'),
    );
  }

  /**
   * Returns info for the provided photo variable.
   */
  public static function getPhotoVariableInfo() {
    return array(
      'type' => 'fluxflickr_photo',
      'label' => t('Photo'),
      'description' => t('The photo that triggered the event.'),
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
      '#description' => t('The service account used for authenticating with the Flickr API.'),
      '#options' => AccountEntity::getOptions('fluxflickr', $form_state['rules_config']),
      '#default_value' => $settings['account'],
      '#required' => TRUE,
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
