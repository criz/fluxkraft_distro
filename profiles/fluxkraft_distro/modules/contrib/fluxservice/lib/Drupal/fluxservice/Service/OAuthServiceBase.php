<?php

/**
 * @file
 * Contains OAuthServiceBase.
 */

namespace Drupal\fluxservice\Service;

use Drupal\fluxservice\Plugin\Entity\Service;
use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Abstract base class for OAuth services.
 */
abstract class OAuthServiceBase extends Service implements OAuthServiceInterface {

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return array(
      'service_url' => '',
      'consumer_key' => '',
      'consumer_secret' => '',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form['consumer_key'] = array(
      '#type' => 'textfield',
      '#title' => t('Consumer key'),
      '#description' => t('The consumer key for authenticating through OAuth.'),
      '#default_value' => $this->getConsumerKey(),
      '#required' => TRUE,
    );

    $form['consumer_secret'] = array(
      '#type' => 'textfield',
      '#title' => t('Consumer secret'),
      '#description' => t('The consumer secret for authenticating through OAuth.'),
      '#default_value' => $this->getConsumerSecret(),
      '#required' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumerKey() {
    return $this->data->get('consumer_key');
  }

  /**
   * {@inheritdoc}
   */
  public function getConsumerSecret() {
    return $this->data->get('consumer_secret');
  }

}
