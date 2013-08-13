<?php

/**
 * @file
 * Contains OAuthAccountBase.
 */

namespace Drupal\fluxservice\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Drupal\fluxservice\Plugin\Entity\Account;
use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;

/**
 * Abstract base class for OAuth accounts.
 */
abstract class OAuthAccountBase extends Account implements OAuthAccountInterface {

  /**
   * {@inheritdoc}
   */
  public static function getAccountForOAuthCallback($key, $plugin) {
    $store = fluxservice_tempstore("fluxservice.account.$plugin");
    return $store->getIfOwner($key);
  }

  /**
   * {@inheritdoc}
   */
  public function accessOAuthCallback() {
    $plugin = $this->bundle();

    // Check if the oauth token is available in the REQUEST and in the SESSION.
    if (!isset($_SESSION[$plugin]['oauth_token']) || !isset($_REQUEST['oauth_token'])) {
      return FALSE;
    }

    // Check if the oauth tokens match.
    if ($_SESSION[$plugin]['oauth_token'] !== $_REQUEST['oauth_token']) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function processOAuthCallback() {
    $plugin = $this->bundle();
    $token = $_REQUEST['oauth_token'];
    $secret = $_SESSION[$plugin]['oauth_token_secret'];
    $verifier = $_REQUEST['oauth_verifier'];

    $service = $this->getService();
    $client = new Client($this->getAccessTokenUrl());
    $client->addSubscriber(new OAuthPlugin(array(
      'consumer_key' => $service->getConsumerKey(),
      'consumer_secret' => $service->getConsumerSecret(),
      'token' => $token,
      'token_secret' => $secret,
      'verifier' => $verifier,
    )));

    // Issue an access token request.
    parse_str($client->post()->send()->getBody(), $response);

    // Give sub-classes a chance to implement custom logic for modifying and
    // saving the response values to the account.
    $this->processAuthorizedAccount($response);

    // Remove the temporarily stored account entity from the tempstore.
    $store = fluxservice_tempstore("fluxservice.account.$plugin");
    $store->delete($this->identifier());
  }

  /**
   * {@inheritdoc}
   */
  public function prepareAccount() {
    // Temporarily save the account entity so we can refer to it later.
    $store = fluxservice_tempstore("fluxservice.account.{$this->bundle()}");
    $store->setIfNotExists($this->identifier(), $this);

    // Authorize the account.
    $this->authorizeAccount();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return array(
      'oauth_token' => '',
      'oauth_token_secret' => '',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form['oauth_token'] = array(
      '#type' => 'item',
      '#title' => t('Access Token'),
      '#markup' => $this->getOauthToken(),
    );

    $form['oauth_token_secret'] = array(
      '#type' => 'item',
      '#title' => t('Access Token Secret'),
      '#markup' => $this->getOauthTokenSecret(),
    );

    return $form;
  }

  /**
   * Retrieves the OAuth request token.
   *
   * @return mixed
   *   The web service response.
   */
  protected function getRequestToken() {
    // Retrieve a fresh request token from the Twitter API.
    $service = $this->getService();
    $client = new Client($this->getRequestTokenUrl());
    $client->addSubscriber(new OAuthPlugin(array(
      'consumer_key' => $service->getConsumerKey(),
      'consumer_secret' => $service->getConsumerSecret(),
      'callback' => $this->getCallbackUrl(),
    )));

    parse_str($client->post()->send()->getBody(), $response);

    return $response;
  }

  /**
   * Issues a authorization request for the given account.
   *
   * @see drupal_goto()
   */
  protected function authorizeAccount() {
    $plugin = $this->bundle();
    $_SESSION[$plugin] = $this->getRequestToken();

    // Unset the the destination so drupal_goto() does not play tricks on us.
    unset($_GET['destination']);

    // Redirect to the authorize url for authenticating the user.
    drupal_goto(url($this->getAuthorizeUrl(), array('query' => array(
      'oauth_token' => $_SESSION[$plugin]['oauth_token'],
    ))));
  }

  /**
   * Processes a authorized account.
   *
   * Can be overridden by sub-classes to implement custom logic for extracting
   * information from the web service response.
   *
   * @param array $response
   *   The response from the web service.
   *
   * @see OAuthAccountBase::authorizeAccount()
   */
  protected function processAuthorizedAccount(array $response) {
    $settings = array_intersect_key($response, $this->getDefaultSettings());
    $this->data->mergeArray($settings);
  }

  /**
   * Retrieves the configured OAuth token for the account.
   *
   * @return string
   *   The configured OAuth token.
   */
  protected function getOauthToken() {
    return $this->data->get('oauth_token');
  }

  /**
   * Retrieves the configured OAuth token secret for the account.
   *
   * @return string
   *   The configured OAuth token secret.
   */
  protected function getOauthTokenSecret() {
    return $this->data->get('oauth_token_secret');
  }

  /**
   * Retrieves the OAuth callback URL.
   *
   * @return string
   *   The callback url.
   */
  protected function getCallbackUrl() {
    $options = array('absolute' => TRUE);
    if (!empty($_GET['destination'])) {
      $options['query']['destination'] = $_GET['destination'];
    }
    return url("fluxservice/oauth/{$this->bundle()}/{$this->identifier()}", $options);
  }

  /**
   * Retrieves the web service's authorization URL.
   *
   * @return string
   *   The authorization url.
   */
  abstract protected function getAuthorizeUrl();

  /**
   * Retrieves the web service's token request URL.
   *
   * @return string
   *   The token request url.
   */
  abstract protected function getRequestTokenUrl();

  /**
   * Retrieves the web service's access token URL.
   *
   * @return string
   *   The access token url.
   */
  abstract protected function getAccessTokenUrl();

}
