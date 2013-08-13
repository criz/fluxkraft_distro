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

}
