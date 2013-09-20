<?php

/**
 * @file
 * Contains TwitterAccount.
 */

namespace Drupal\fluxtwitter\Plugin\Service;

use Drupal\fluxservice\Service\OAuthAccountBase;
use Drupal\fluxtwitter\TwitterClient;

/**
 * Account plugin implementation for Twitter.
 */
class TwitterAccount extends OAuthAccountBase implements TwitterAccountInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtwitter',
      'label' => t('Twitter account'),
      'description' => t('Provides Twitter integration for fluxkraft.'),
      'service' => 'fluxtwitter',
    );
  }

  /**
   * The service base url.
   *
   * @var string
   */
  protected $serviceUrl = 'https://api.twitter.com';

  /**
   * {@inheritdoc}
   */
  public static function getPropertyDefinitions() {
    $properties['screen_name'] = array(
      'label' => t('Screen name'),
      'description' => t('The user name.'),
      'getter callback' => 'fluxservice_entity_metadata_get_account_detail',
      'type' => 'text',
      'entity views field' => TRUE,
    );

    $properties['location'] = array(
      'label' => t('Location'),
      'description' => t('The location.'),
      'getter callback' => 'fluxservice_entity_metadata_get_account_detail',
      'type' => 'text',
      'entity views field' => TRUE,
    );

    $properties['description'] = array(
      'label' => t('Description'),
      'description' => t('The description.'),
      'getter callback' => 'fluxservice_entity_metadata_get_account_detail',
      'type' => 'text',
      'entity views field' => TRUE,
    );

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function client() {
    $service = $this->getService();
    return TwitterClient::factory(array(
      'base_url' => "$this->serviceUrl/1.1",
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
    // Build the label and remote id from the response data.
    $this->setRemoteIdentifier($response['user_id'])->setLabel($response['screen_name']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getAuthorizeUrl() {
    return "$this->serviceUrl/oauth/authorize";
  }

  /**
   * {@inheritdoc}
   */
  protected function getRequestTokenUrl() {
    return "$this->serviceUrl/oauth/request_token";
  }

  /**
   * {@inheritdoc}
   */
  protected function getAccessTokenUrl() {
    return "$this->serviceUrl/oauth/access_token";
  }

}
