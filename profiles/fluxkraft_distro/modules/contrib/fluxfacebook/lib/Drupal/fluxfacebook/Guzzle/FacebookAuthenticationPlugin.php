<?php

/**
 * @file
 * Contains FacebookOAuthPlugin.
 */

namespace Drupal\fluxfacebook\Guzzle;

use Guzzle\Common\Collection;
use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Guzzle plugin for Facebook OAuth integration.
 */
class FacebookAuthenticationPlugin implements EventSubscriberInterface {

  /**
   * Configuration settings
   *
   * @var Collection
   */
  protected $config;

  /**
   * Constructs a new FacebookOAuthPlugin object.
   */
  public function __construct($config) {
    $this->config = Collection::fromConfig($config, array(), array('client_id', 'client_secret'));

    if (($token = $this->config->get('access_token')) === NULL) {
      $token = "{$this->config->get('client_id')}|{$this->config->get('client_secret')}";
      $this->config->set('access_token', $token);
    }

    if ($this->config->get('appsecret_proof') === NULL) {
      $this->config->set('appsecret_proof', hash_hmac('sha256', $token, $this->config->get('client_secret')));
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return array(
      'request.before_send' => array('onRequestBeforeSend', -1000)
    );
  }

  /**
   * {@inheritdoc}
   */
  public function onRequestBeforeSend(Event $event) {
    $request = $event['request'];
    $params = $this->config->getAll(array('client_id', 'client_secret', 'access_token', 'appsecret_proof'));
    $request->getQuery()->merge($params);
  }

}
