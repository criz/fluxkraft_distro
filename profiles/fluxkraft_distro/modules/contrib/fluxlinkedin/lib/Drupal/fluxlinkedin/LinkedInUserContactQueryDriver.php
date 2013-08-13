<?php

/**
 * @file
 *   Contains LinkedInUserContactQueryDriver.
 */

namespace Drupal\fluxlinkedin;

use Drupal\fluxservice\Query\RangeRemoteEntityQueryDriverBase;

/**
 * Gets users via the authorised users contacts.
 */
class LinkedInUserContactQueryDriver extends RangeRemoteEntityQueryDriverBase {

  /**
   * The max limit of the LinkedIn API.
   * @var int
   */
  protected $maxLimit = 500;

  /**
   * The fields to select.
   *
   * @var array
   */
  protected $fields = array("id", "first-name", "last-name", "headline", "picture-url");

  /**
   * Prepare executing the query.
   *
   * This may be used to check dependencies and to prepare request parameters.
   */
  protected function prepareExecute(\EntityFieldQuery $query) {
    parent::prepareExecute($query);
  }

  /**
   * Overridden.
   */
  protected function makeRequest() {
    $account = $this->getAccount();
    // Also add all request parameters which do not equal FALSE.
    $params = array_filter($this->requestParameter, function($value) {
      return $value !== FALSE;
    });
    $response = $account->client()->getConnectionsById($params + array(
      'id' => $account->getRemoteIdentifier(),
      'fields' => $this->fields,
      'format' => 'json',
    ));
    return isset($response['values']) ? $response['values'] : array();
  }

  /**
   * Overridden.
   */
  protected function makeCountRequest() {
    $account = $this->getAccount();
    $response = $account->client()->getConnectionsById(array(
      'id' => $account->getRemoteIdentifier(),
      'fields' => $this->fields,
      'format' => 'json'
    ));
    return $response['_total'];
  }

  /**
   * Overridden.
   */
  protected function getLimit() {
    return isset($this->requestParameter['count']) ? $this->requestParameter['count'] : FALSE;
  }

  /**
   * Overridden.
   */
  protected function setLimit($start) {
    $this->requestParameter['count'] = $start;
  }

  /**
   * Overridden.
   */
  protected function getOffset() {
    return isset($this->requestParameter['start']) ? $this->requestParameter['start'] : FALSE;
  }

  /**
   * Overridden.
   */
  protected function setOffset($offset) {
    $this->requestParameter['start'] = $offset;
  }

  /**
   * {@inheritdoc}
   */
  public function getAccountPlugin() {
    return 'fluxlinkedin';
  }
}
