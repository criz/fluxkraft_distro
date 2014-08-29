<?php

/**
 * @file
 *   Contains TwitterUserContactQueryDriver.
 */

namespace Drupal\fluxtwitter;

use Drupal\fluxservice\Query\RangeRemoteEntityQueryDriverBase;

/**
 * EFQ query driver for Twitter tweets.
 */
class TwitterTweetEntityQueryDriver extends RangeRemoteEntityQueryDriverBase {

  /**
   * Prepare executing the query.
   *
   * This may be used to check dependencies and to prepare request parameters.
   */
  protected function prepareExecute(\EntityFieldQuery $query) {
    parent::prepareExecute($query);
    if (isset($query->range['length'])) {
      $this->requestParameter = array('count' => intval($query->range['length']));
    }
  }

  /**
   * Make a request.
   *
   * @return array
   */
  protected function makeRequest() {
    $response = $this->getAccount()->client()->GetUserTimeline($this->requestParameter);
    return $response;
  }

  /**
   * Runs the count query.
   */
  protected function makeCountRequest() {
    $response = $this->getAccount()->client()->GetUserTimeline(array('count' => 200));
    return count($response);
  }

  /**
   * {@inheritdoc}
   */
  public function getAccountPlugin() {
    return 'fluxtwitter';
  }
}
