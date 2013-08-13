<?php

/**
 * @file
 * Contains RangeRemoteEntityQueryDriverBase.
 */

namespace Drupal\fluxservice\Query;

/**
 * A helper class for executing repeated requests to process the query range.
 */
abstract class RangeRemoteEntityQueryDriverBase extends RemoteEntityQueryDriverBase {

  /**
   * An array of request parameters for the remote service requests.
   *
   * @var array
   */
  protected $requestParameter = array();

  /**
   * The maximum number of items that can be requested in one call.
   *
   * FALSE means there is no limit.
   *
   * @var int|false
   */
  protected $maxLimit = 100;

  /**
   * The number of requested items, or FALSE if all items are requested.
   *
   * @var int|false
   */
  protected $requestedLimit = FALSE;

  /**
   * Whether the responce can be entifyed and bycatched.
   *
   * @var bool
   */
  protected $byCatch = TRUE;

  /**
   * {@inheritdoc}
   */
  public function execute(\EntityFieldQuery $query) {
    $this->prepareExecute($query);

    if ($query->count) {
      return $this->makeCountRequest($query);
    }

    if ($query->range) {
      $this->setOffset($query->range['start']);
      $this->setLimit($query->range['length']);
    }

    $this->requestedLimit = $this->getLimit();

    // Make so many requests with the max limit as long as required.
    if ($this->maxLimit && (!$this->requestedLimit || $this->requestedLimit > $this->maxLimit)) {
      $this->setLimit($this->maxLimit);
    }

    // Get results as long as required.
    $results = $this->makeRequest();
    $last_results = $results;

    while ($this->maxLimit !== FALSE && count($last_results) == $this->maxLimit) {
      // Calculate the number of missing results and set new limit and offset.
      $missing = $this->requestedLimit - count($results);
      $this->setLimit(max($missing, $this->maxLimit));
      $this->setOffset($this->getOffset() + $this->maxLimit);

      $last_results = $this->makeRequest();
      $results = array_merge($results, $last_results);
    }
    return $this->processResults($results);
  }

  /**
   * Processes the results to be returned in the form as required by EFQ.
   *
   * @return
   *   See EntityFieldQuery::execute().
   */
  protected function processResults(array $results) {
    // Keep a copy of the results in the original ordering.
    $this->order_results = $results;

    // Entify results into the entity storage controller so it's cached.
    if ($this->byCatch) {
      fluxservice_entify_bycatch_multiple($results, $this->entityType, $this->account);
    }

    $return = array();
    foreach ($results as $result) {
      $id = $this->getAccount()->internalIdentifier() . ':' . $result[$this->entityInfo['entity keys']['remote id']];
      $return[$this->entityType][$id] = entity_create_stub_entity($this->entityType, array($id));;
    }
    return $return;
  }

  /**
   * Gets the limit request parameter.
   *
   * @return integer
   */
  protected function getLimit() {
    return isset($this->requestParameter['limit']) ? $this->requestParameter['limit'] : FALSE;
  }

  /**
   * Sets the limit request parameter.
   */
  protected function setLimit($start) {
    $this->requestParameter['limit'] = $start;
  }

  /**
   * Gets the offset request parameter.
   *
   * @return integer
   */
  protected function getOffset() {
    return isset($this->requestParameter['offset']) ? $this->requestParameter['offset'] : FALSE;
  }

  /**
   * Sets the offset request parameter.
   */
  protected function setOffset($offset) {
    $this->requestParameter['offset'] = $offset;
  }

  /**
   * Make a request by taking the request parameters into account.
   *
   * @return array
   *   The response array.
   */
  abstract protected function makeRequest();

  /**
   * Runs the count request by taking the request parameters into account.
   *
   * @return integer
   *   The count.
   */
  abstract protected function makeCountRequest();

  /**
   * Prepare executing the query.
   *
   * This may be used to check dependencies and to prepare request parameters.
   */
  protected function prepareExecute(\EntityFieldQuery $query) {
    // Nothing to do by default.
  }

}
