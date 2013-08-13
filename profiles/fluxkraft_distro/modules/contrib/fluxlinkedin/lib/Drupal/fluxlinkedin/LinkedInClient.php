<?php

/**
 * @file
 * Contains LinkedInUserClient.
 */

namespace Drupal\fluxlinkedin;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Guzzle driven service client for the LinkedIn API.
 */
class LinkedInClient extends Client {

  /**
   * {@inheritdoc}
   */
  public static function factory($config = array()) {
    $required = array('consumer_key', 'consumer_secret', 'token', 'token_secret');
    $defaults = array(
      'base_url' => 'http://api.linkedin.com/v1/',
    );

    $config = Collection::fromConfig($config, $defaults, $required);
    $client = new static($config->get('base_url'), $config);

    // Attach a service description to the client
    $description = ServiceDescription::factory(__DIR__ . '/linkedin.json');
    $client->setDescription($description);

    // Add the OAuth plugin as an event subscriber using the credentials given
    // in the configuration array.
    $client->addSubscriber(new OauthPlugin(array(
      'consumer_key' => $config->get('consumer_key'),
      'consumer_secret' => $config->get('consumer_secret'),
      'token' => $config->get('token'),
      'token_secret' => $config->get('token_secret'),
    )));

    return $client;
  }

}
