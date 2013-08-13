<?php

/**
 * @file
 *   Contains XingUserContactQueryDriver.
 */

namespace Drupal\fluxxing;

use Drupal\fluxservice\Query\RangeRemoteEntityQueryDriverBase;

/**
 * Gets users via the authorised users contacts.
 */
class XingUserContactQueryDriver extends RangeRemoteEntityQueryDriverBase {

  /**
   * Prepare executing the query.
   *
   * This may be used to check dependencies and to prepare request parameters.
   */
  protected function prepareExecute(\EntityFieldQuery $query) {
    parent::prepareExecute($query);
    $this->requestParameter = array('user_fields' => array('first_name', 'last_name', 'photo_urls'));
  }

  /**
   * Make a request.
   *
   * @return array
   */
  protected function makeRequest() {
    $response = $this->getAccount()->client()->getContacts($this->requestParameter);
    if (!$this->requestedLimit) {
      $this->requestedLimit = $response['contacts']['total'];
    }
    return $response['contacts']['users'];
  }

  /**
   * Runs the count query.
   */
  protected function makeCountRequest() {
    // With limit=0 one can just get the count.
    $response = $this->getAccount()->client()->getContacts(array('limit' => 0));
    return $response['contacts']['total'];
  }

  /**
   * {@inheritdoc}
   */
  public function getAccountPlugin() {
    return 'fluxxing';
  }
}
