<?php

/**
 * @file
 * Contains TwitterUserClient.
 */

namespace Drupal\fluxfacebook;

use Drupal\fluxfacebook\Guzzle\FacebookAuthenticationPlugin;
use Drupal\fluxservice\ServiceClientInterface;
use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Guzzle driven service client for the Twitter API.
 */
class FacebookGraphClient extends Client {

  /**
   * {@inheritdoc}
   */
  public static function factory($config = array()) {
    $required = array('client_id', 'client_secret', 'access_token');
    $config = Collection::fromConfig($config, array(), $required);
    $client = new static('', $config);

    // Attach a service description to the client
    $description = ServiceDescription::factory(__DIR__ . '/facebook.graph.json');
    $client->setDescription($description);

    // Add the OAuth plugin as an event subscriber using the credentials given
    // in the configuration array.
    $client->addSubscriber(new FacebookAuthenticationPlugin(array(
      'client_id' => $config->get('client_id'),
      'client_secret' => $config->get('client_secret'),
      'access_token' => $config->get('access_token'),
      'appsecret_proof' => $config->get('appsecret_proof'),
    )));

    return $client;
  }

}
