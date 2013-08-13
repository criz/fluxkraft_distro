<?php

/**
 * @file
 * Contains FacebookAccount.
 */

namespace Drupal\fluxfacebook\Plugin\Service;

use Drupal\fluxfacebook\FacebookGraphClient;
use Drupal\fluxfacebook\Plugin\Service\FacebookAccountInterface;
use Drupal\fluxservice\Plugin\Entity\Account;
use Guzzle\Http\Client;
use Guzzle\Http\Url;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Account implementation for Facebook.
 */
class FacebookAccount extends Account implements FacebookAccountInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxfacebook',
      'label' => t('Facebook account'),
      'description' => t('Provides Facebook integration for fluxkraft.'),
      'class' => '\Drupal\fluxfacebook\Plugin\Service\FacebookAccount',
      'service' => 'fluxfacebook',
    );
  }

  /**
   * The service base url.
   *
   * @var string
   */
  protected $serviceUrl = 'https://graph.facebook.com';

  /**
   * {@inheritdoc}
   */
  public static function getAccountForOAuthCallback($key, $plugin) {
    $store = fluxservice_tempstore("fluxservice.account.{$plugin}");
    return $store->getIfOwner($key);
  }

  /**
   * {@inheritdoc}
   */
  public function client() {
    $service = $this->getService();
    return FacebookGraphClient::factory(array(
      'client_id' => $service->getConsumerKey(),
      'client_secret' => $service->getConsumerSecret(),
      'access_token' => $this->getAccessToken(),
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function prepareAccount() {
    parent::prepareAccount();

    // Generate a 'state' token for later comparison. The redirect url has to
    // be saved as well because the Facebook API requires us to send the same
    // redirect url in the access token request step.
    $state = drupal_get_token(serialize($this));
    $redirect = $this->getRedirectUrl($this);

    $settings = $this->data;
    $settings->set('state', $state);
    $settings->set('redirect', $redirect);

    // Temporarily save the account entity so we can refer to it later.
    $store = fluxservice_tempstore("fluxservice.account.{$this->bundle()}");
    $store->setIfNotExists($this->identifier(), $this);

    drupal_goto(url('https://www.facebook.com/dialog/oauth', array('query' => array(
      'app_id' => $this->getService()->getConsumerKey(),
      'redirect_uri' => $redirect,
      'state' => $state,
      'scope' => implode(',', $this->getRequiredPermissions())
    ))));
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return array(
      'access_token' => '',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form['access_token'] = array(
      '#type' => 'item',
      '#title' => t('Access token'),
      '#markup' => $this->getAccessToken(),
    );

    return $form;
  }

  /**
   * Builds the URL to redirect to after visiting facebook for authentication.
   *
   * Note: This URL does NOT support query parameters due to restrictions
   * enforced by the Facebook API.
   *
   * @return string
   *   The URL to redirect to after visiting the Facebook OAauth endpoint for
   *   requesting access privileges from a user.
   *
   * @see FacebookAccount::getRedirectDestination()
   */
  protected function getRedirectUrl() {
    return url("fluxservice/oauth/{$this->bundle()}/{$this->identifier()}", array('absolute' => TRUE));
  }

  /**
   * Retrieves a list of required permissions.
   *
   * Other modules may implement the fluxfacebook_required_permissions() hook to
   * extend provide additional required permissions.
   *
   * @return array
   *   The required permissions.
   *
   * @see fluxfacebook_fluxfacebook_required_permissions()
   */
  protected function getRequiredPermissions() {
    $permissions = module_invoke_all('fluxfacebook_required_permissions', $this);
    return array_unique($permissions);
  }

  /**
   * {@inheritdoc}
   */
  public function accessOAuthCallback() {
    // Ensure that all required request and account values are set.
    if (!isset($_REQUEST['code']) || !isset($_REQUEST['state']) || !$state = $this->data->get('state')) {
      return FALSE;
    }

    // Check if the 'state' variable probided in the request matches the token
    // stored in the account entity to ensure that this is definitely the same
    // user that originally issued the account creation request.
    if ($_REQUEST['state'] !== $state) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function processOAuthCallback() {
    // Request a short-lived access token.
    $response = $this->requestAccessToken($_REQUEST['code'], $this->data->get('redirect'));

    // Request an extended access token directly afterwards.
    $response = $this->requestExtendedAccessToken($response['access_token']);

    // Do some additional processing (e.g. loading some account information
    // like the account name through the Facebook API).
    $this->processAuthorizedAccount($response);

    // Remove the temporarily stored account entity from the tempstore.
    $store = fluxservice_tempstore("fluxservice.account.{$this->bundle()}");
    $store->delete($this->identifier());
  }

  /**
   * Helper function for loading Facebook profile data into a linked account.
   *
   * @param array $response
   *   The response from the access token GET request.
   */
  protected function processAuthorizedAccount(array $response) {
    $settings = array_intersect_key($response, $this->getDefaultSettings());
    $this->data->mergeArray($settings);

    $response = $this->client()->getMe(array('fields' => array('id', 'name')));
    $this->setLabel($response['name'])
         ->setRemoteIdentifier($response['id']);
  }

  /**
   * Retrieves an expirable access token from the Facebook API.
   *
   * @param string $code
   *   The access code returned from the Facebook API login step.
   * @param string $redirect
   *   The URL to redirect to. This has to match the redirect URL used in the
   *   login step.
   *
   * @return array
   *   An array containing the requested access token and its expiry date.
   */
  public function requestAccessToken($code, $redirect) {
    $service = $this->getService();

    // Retrieve a fresh request token from the Twitter API.
    $client = new Client($this->serviceUrl);

    // Add the request parameters.
    $request = $client->post('oauth/access_token');
    $request->addPostFields(array(
      'client_id' => $service->getConsumerKey(),
      'client_secret' => $service->getConsumerSecret(),
      // The Facebook API requires the same redirect uri used for the login step
      // when requesting the access token.
      'redirect_uri' => $redirect,
      'code' => $code,
    ));

    // Issue an access token request.
    parse_str($request->send()->getBody(), $response);

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function requestExtendedAccessToken($token) {
    $service = $this->getService();

    // Extend a previously requested access token.
    $client = new Client($this->serviceUrl);

    // Add the request parameters.
    $request = $client->post('oauth/access_token');
    $request->addPostFields(array(
      'grant_type' => 'fb_exchange_token',
      'client_id' => $service->getConsumerKey(),
      'client_secret' => $service->getConsumerSecret(),
      'fb_exchange_token' => $token,
    ));

    // Issue an access token request.
    parse_str($request->send()->getBody(), $response);

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function getAccessToken() {
    return $this->data->get('access_token');
  }

}
