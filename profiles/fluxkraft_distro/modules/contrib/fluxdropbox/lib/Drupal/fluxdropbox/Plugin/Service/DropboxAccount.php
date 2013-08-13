<?php

/**
 * @file
 * Contains DropboxAccount.
 */

namespace Drupal\fluxdropbox\Plugin\Service;

use Dropbox\Exception;
use Drupal\fluxdropbox\DropboxClient;
use Drupal\fluxdropbox\DropboxAccountStorage;
use Drupal\fluxservice\Plugin\Entity\Account;
use Dropbox\OAuth\Storage\Encrypter;
use Dropbox\OAuth\Consumer\Curl;
use Dropbox\API;

/**
 * Account plugin implementation for Dropbox.
 */
class DropboxAccount extends Account implements DropboxAccountInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxdropbox',
      'label' => t('Dropbox account'),
      'description' => t('Provides Dropbox integration for fluxkraft.'),
      'service' => 'fluxdropbox',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function prepareAccount() {
    parent::prepareAccount();
    $key = $this->getService()->getConsumerKey();
    $secret = $this->getService()->getConsumerSecret();

    unset($_SESSION['dropbox_api']);

    $storage = $this->getStorage();

    // Temporarily save the account entity so we can refer to it later.
    $store = fluxservice_tempstore("fluxservice.account.{$this->bundle()}");
    $store->setIfNotExists($this->identifier(), $this);


    $redirect = $this->getRedirectUrl();

    // Redirects the user to the authentication site of dropbox,
    // and returns the user upon success to the redirect url.
    new Curl($key, $secret, $storage, $redirect);
  }

  /**
   * {@inheritdoc}
   */
  public static function getAccountForOAuthCallback($key, $plugin) {
    $store = fluxservice_tempstore("fluxservice.account.{$plugin}");
    return $store->getIfOwner($key);
  }

  /**
   * Builds the URL to redirect to after visiting dropbox for authentication.
   *
   *
   * @return string
   *   The URL to redirect to after visiting the Dropbox OAauth endpoint for
   *   requesting access privileges from a user.
   */
  protected function getRedirectUrl() {
    return url("fluxservice/oauth/{$this->bundle()}/{$this->identifier()}", array('absolute' => TRUE));
  }

  /**
   * {@inheritdoc}
   */
  public function client() {
    $service = $this->getService();
    $storage = $this->getStorage();
    return DropboxClient::factory(array(
      'client_id' => $service->getConsumerKey(),
      'client_secret' => $service->getConsumerSecret(),
      'storage' => $storage,
    ));
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
  public function accessOAuthCallback() {
    // Ensure that all required request and account values are set.
    if (!isset($_REQUEST['oauth_token'])) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function processOAuthCallback() {

    $key = $this->getService()->getConsumerKey();
    $secret = $this->getService()->getConsumerSecret();
    $storage = $this->getStorage();

    // Gets an authentication token from Dropbox.
    new Curl($key, $secret, $storage, NULL);

    $this->processAuthorizedAccount();

    // Remove the temporarily stored account entity from the tempstore.
    $store = fluxservice_tempstore("fluxservice.account.{$this->bundle()}");
    $store->delete($this->identifier());
  }

  /**
   * Create the storage object.
   */
  public function getStorage() {
    return new DropboxAccountStorage($this);
  }

  /**
   * {@inheritdoc}
   */
  protected function processAuthorizedAccount() {
    // Build the label and remote id from the response data.
    $account_info = $this->client()->accountInfo();
    $this->setRemoteIdentifier($account_info['body']->uid)->setLabel($account_info['body']->display_name);
  }
}
