<?php

/**
 * @file
 * Contains LinkedInAccount.
 */

namespace Drupal\fluxlinkedin\Plugin\Service;

use Drupal\fluxlinkedin\LinkedInClient;
use Drupal\fluxservice\Service\OAuthAccountBase;

/**
 * Account plugin implementation for LinkedIn.
 */
class LinkedInAccount extends OAuthAccountBase implements LinkedInAccountInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxlinkedin',
      'label' => t('LinkedIn account'),
      'description' => t('Provides LinkedIn integration for fluxkraft.'),
      'class' => '\Drupal\fluxlinkedin\Plugin\ServiceAccount\LinkedInAccountHandler',
      'service' => 'fluxlinkedin',
    );
  }

  /**
   * The service base url.
   *
   * @var string
   */
  protected $serviceUrl = 'https://api.linkedin.com';

  /**
   * {@inheritdoc}
   */
  public function client() {
    $service = $this->getService();
    return LinkedInClient::factory(array(
      'base_url' => "$this->serviceUrl/v1",
      'consumer_key' => $service->getConsumerKey(),
      'consumer_secret' => $service->getConsumerSecret(),
      'token' => $this->getOauthToken(),
      'token_secret' => $this->getOauthTokenSecret(),
    ));
  }

  /**
   * {@inheritdoc}
   */
  protected function getAuthorizeUrl() {
    return "$this->serviceUrl/uas/oauth/authorize";
  }

  /**
   * {@inheritdoc}
   */
  protected function getRequestTokenUrl() {
    return "$this->serviceUrl/uas/oauth/requestToken?scope=r_network+r_emailaddress+r_basicprofile+w_messages";
  }

  /**
   * {@inheritdoc}
   */
  protected function getAccessTokenUrl() {
    return "$this->serviceUrl/uas/oauth/accessToken";
  }

  /**
   * {@inheritdoc}
   */
  protected function processAuthorizedAccount(array $response) {
    parent::processAuthorizedAccount($response);
    // Build the label and remote id from the response data.
    $response = $this->client()->getMe();
    $this->setRemoteIdentifier($response['id'])->setLabel("{$response['firstName']} {$response['lastName']}");
  }

}
