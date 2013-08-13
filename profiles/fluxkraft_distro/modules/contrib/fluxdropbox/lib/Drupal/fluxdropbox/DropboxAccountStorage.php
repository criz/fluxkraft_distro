<?php

/**
 * @file
 * Contains DropboxAccountStorage.
 */

namespace Drupal\fluxdropbox;

use Dropbox\OAuth\Storage\StorageInterface;
use Dropbox\Exception;

/**
 * Storage class storing information in the account entity.
 */
class DropboxAccountStorage implements StorageInterface {

  /**
   * Fluxservice account.
   *
   * @var \Drupal\fluxservice\Plugin\Entity\AccountInterface
   */
  protected $account = NULL;

  /**
   * Session namespace.
   *
   * @var string
   */
  protected $namespace = 'fluxdropbox';

  /**
   * Constructs the object.
   */
  public function __construct($account) {
    // Set the account.
    $this->account = $account;
  }

  /**
   * Get an OAuth token from the account entity.
   *
   * @param string $type
   *   The token type to retrieve.
   *
   * @return array|bool
   */
  public function get($type) {
    if ($type != 'request_token' && $type != 'access_token') {
      throw new Exception("Expected a type of either 'request_token' or 'access_token', got '$type'");
    }
    elseif ($type == 'request_token') {
      if ($token = $_SESSION[$this->namespace]) {
        return $token;
      }
      return FALSE;
    }
    else {
      if ($token = $this->account->data->get($type)) {
        return $token;
      }
      return FALSE;
    }
  }

  /**
   * Sets an OAuth token in the account entity by type.
   *
   * @param \stdClass $token
   *   The token object to set.
   * @param string $type
   *   The token type.
   */
  public function set($token, $type) {
    if ($type != 'request_token' && $type != 'access_token') {
      throw new Exception("Expected a type of either 'request_token' or 'access_token', got '$type'");
    }
    elseif ($type == 'request_token') {
      $_SESSION[$this->namespace] = array();
      $_SESSION[$this->namespace] = $token;
    }
    else {
      $settings = array(
        'access_token' => $token,
      );
      $this->account->data->mergeArray($settings);
    }
  }

  /**
   * Deletes the request and access tokens currently stored in the session.
   *
   * @return bool
   */
  public function delete() {
    unset($this->account->data->request_token);
    unset($this->account->data->access_token);
    return TRUE;
  }
}
