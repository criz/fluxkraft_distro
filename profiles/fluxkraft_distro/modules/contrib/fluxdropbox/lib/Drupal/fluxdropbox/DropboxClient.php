<?php

/**
 * @file
 * Contains DropboxUserClient.
 */

namespace Drupal\fluxdropbox;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client;
use Dropbox\API;
use Dropbox\OAuth\Consumer\Curl;

/**
 * Service client for the Dropbox API.
 */
class DropboxClient extends Client {

  /**
   * {@inheritdoc}
   */
  public static function factory($config = array()) {
    $oauth = new Curl($config['client_id'], $config['client_secret'], $config['storage'], NULL);
    $client = new API($oauth);
    return $client;
  }
}
