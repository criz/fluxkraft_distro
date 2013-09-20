<?php

/**
 * @file
 * Contains FacebookService.
 */

namespace Drupal\fluxfacebook\Plugin\Service;

use Drupal\fluxservice\Plugin\Entity\Service;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Service plugin implementation for Facebook.
 */
class FacebookService extends Service implements FacebookServiceInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxfacebook',
      'label' => t('Facebook'),
      'description' => t('Provides Facebook integration for fluxkraft.'),
      'class' => '\Drupal\fluxfacebook\Plugin\Service\FacebookService',
      'icon' => 'images/fluxicon_facebook.svg',
      'icon background color' => '#3b5998',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return array(
      'service_url' => '',
      'application_id' => '',
      'application_secret' => '',
      'polling_interval' => 900,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {

    $form['help'] = array(
      '#type' => 'markup',
      '#markup' => t('In the following, you need to provide authentication details for communicating with Facebook.<br/>For that, you have to register as facebook developer and register an application in the <a href="https://developers.facebook.com/">Facebook App developer settings</a>.'),
      '#prefix' => '<p class="fluxservice-help">',
      '#suffix' => '</p>',
      '#weight' => -1,
    );

    $form['application_id'] = array(
      '#type' => 'textfield',
      '#title' => t('Application identifier'),
      '#default_value' => $this->getConsumerKey(),
    );

    $form['application_secret'] = array(
      '#type' => 'textfield',
      '#title' => t('Application secret'),
      '#default_value' => $this->getConsumerSecret(),
    );

    $form['rules']['polling_interval'] = array(
      '#type' => 'select',
      '#title' => t('Polling interval'),
      '#default_value' => $this->getPollingInterval(),
      '#options' => array(0 => t('Every cron run')) + drupal_map_assoc(array(300, 900, 1800, 3600, 10800, 21600, 43200, 86400, 604800), 'format_interval'),
      '#description' => t('The time to wait before checking for updates. Note that the effecitive update interval is limited by how often the cron maintenance task runs. Requires a correctly configured <a href="@cron">cron maintenance task</a>.', array('@cron' => url('admin/reports/status'))),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumerKey() {
    return $this->data->get('application_id');
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumerSecret() {
    return $this->data->get('application_secret');
  }

  /**
   * {@inheritdoc}
   */
  public function getPollingInterval() {
    return $this->data->get('polling_interval');
  }

  /**
   * {@inheritdoc}
   */
  public function setPollingInterval($interval) {
    $this->data->set('polling_interval', $interval);
    return $this;
  }

}
