<?php

/**
 * @file
 * Contains TwitterUserClient.
 */

namespace Drupal\fluxtwitter;

use Drupal\fluxservice\ServiceClientInterface;
use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Guzzle driven service client for the Twitter API.
 */
class TwitterClient extends Client {

  /**
   * {@inheritdoc}
   */
  public static function factory($config = array()) {
    $required = array('base_url', 'consumer_key', 'consumer_secret', 'token', 'token_secret');
    $defaults = array(
      'base_url' => 'https://api.twitter.com/1.1',
    );

    $config = Collection::fromConfig($config, $defaults, $required);
    $client = new static($config->get('base_url'), $config);

    // Attach a service description to the client
    $description = ServiceDescription::factory(__DIR__ . '/twitter.json');
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
