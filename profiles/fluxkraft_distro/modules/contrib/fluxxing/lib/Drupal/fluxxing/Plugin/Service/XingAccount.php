<?php

/**
 * @file
 * Contains XingAccount.
 */

namespace Drupal\fluxxing\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountBase;
use Drupal\fluxxing\XingClient;

/**
 * Account plugin implementation for Xing.
 */
class XingAccount extends OAuthAccountBase implements XingAccountInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxxing',
      'label' => t('Xing account'),
      'description' => t('Provides Xing integration for fluxkraft.'),
      'class' => '\Drupal\fluxxing\Plugin\ServiceAccount\XingAccountHandler',
      'service' => 'fluxxing',
    );
  }

  /**
   * The service base url.
   *
   * @var string
   */
  protected $serviceUrl = 'https://api.xing.com/v1';

  /**
   * {@inheritdoc}
   */
  public function client() {
    $service = $this->getService();
    return XingClient::factory(array(
      'base_url' => "$this->serviceUrl",
      'consumer_key' => $service->getConsumerKey(),
      'consumer_secret' => $service->getConsumerSecret(),
      'token' => $this->getOauthToken(),
      'token_secret' => $this->getOauthTokenSecret(),
    ));
  }

  /**
   * {@inheritdoc}
   */
  protected function processAuthorizedAccount(array $response) {
    parent::processAuthorizedAccount($response);
    // Build the remote id from the response data.
    $this->setRemoteIdentifier($response['user_id']);

    // Retrieve the display id through the web service.
    $response = $this->client()->getMe();
    $this->setLabel($response['users'][0]['display_name']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getAuthorizeUrl() {
    return "$this->serviceUrl/authorize";
  }

  /**
   * {@inheritdoc}
   */
  protected function getRequestTokenUrl() {
    return "$this->serviceUrl/request_token";
  }

  /**
   * {@inheritdoc}
   */
  protected function getAccessTokenUrl() {
    return "$this->serviceUrl/access_token";
  }

}
