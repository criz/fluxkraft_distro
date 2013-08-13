<?php

/**
 * @file
 * Contains FlickrAccount.
 */

namespace Drupal\fluxflickr\Plugin\Service;

use Drupal\fluxflickr\FlickrClient;
use Drupal\fluxservice\Service\OAuthAccountBase;
use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Account plugin implementation for Flickr.
 */
class FlickrAccount extends OAuthAccountBase implements FlickrAccountInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxflickr',
      'label' => t('Flickr account'),
      'description' => t('Provides Flickr integration for fluxkraft.'),
      'service' => 'fluxflickr',
    );
  }

  /**
   * The service base url.
   *
   * @var string
   */
  protected $serviceUrl = 'http://www.flickr.com/services';

  /**
   * {@inheritdoc}
   */
  public function client() {
    $service = $this->getService();
    return FlickrClient::factory(array(
      'base_url' => "http://api.flickr.com/services/rest/",
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
    $this->setRemoteIdentifier($response['user_nsid'])->setLabel($response['fullname']);
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
