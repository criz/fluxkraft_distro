<?php

/**
 * @file
 * Contains DatabaseStorageExpirable.
 */

namespace Drupal\fluxservice\KeyValueStore;

/**
 * Defines a default key/value store implementation for expiring items.
 *
 * This key/value store implementation uses the database to store key/value
 * data with an expire date.
 */
class DatabaseStorageExpirable extends DatabaseStorage implements KeyValueStoreExpirableInterface {

  /**
   * The connection object for this storage.
   *
   * @var \DatabaseConnection
   */
  protected $connection;

  /**
   * Flag indicating whether garbage collection should be performed.
   *
   * When this flag is TRUE, garbage collection happens at the end of the
   * request when the object is destructed. The flag is set during set and
   * delete operations for expirable data, when a write to the table is already
   * being performed. This eliminates the need for an external system to remove
   * stale data.
   *
   * @var bool
   */
  protected $needsGarbageCollection = FALSE;

  /**
   * Constructs a DatabaseStorageExpirable object.
   */
  public function __construct($collection, \DatabaseConnection $connection, $table = 'fluxservice_key_value_expire') {
    parent::__construct($collection, $connection, $table);
  }

  /**
   * {@inheritdoc}
   */
  public function getMultiple(array $keys) {
    $values = $this->connection->query(
      'SELECT name, value FROM {' . $this->connection->escapeTable($this->table) . '} WHERE expire > :now AND name IN (:keys) AND collection = :collection',
      array(
        ':now' => REQUEST_TIME,
        ':keys' => $keys,
        ':collection' => $this->collection,
      ))->fetchAllKeyed();
    return array_map('unserialize', $values);
  }

  /**
   * {@inheritdoc}
   */
  public function getAll() {
    $values = $this->connection->query(
      'SELECT name, value FROM {' . $this->connection->escapeTable($this->table) . '} WHERE collection = :collection AND expire > :now',
      array(
        ':collection' => $this->collection,
        ':now' => REQUEST_TIME
      ))->fetchAllKeyed();
    return array_map('unserialize', $values);
  }

  /**
   * {@inheritdoc}
   */
  function setWithExpire($key, $value, $expire) {
    // We are already writing to the table, so perform garbage collection at
    // the end of this request.
    $this->needsGarbageCollection = TRUE;
    $this->connection->merge($this->table)
      ->key(array(
        'name' => $key,
        'collection' => $this->collection,
      ))
      ->fields(array(
        'value' => serialize($value),
        'expire' => REQUEST_TIME + $expire,
      ))
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  function setWithExpireIfNotExists($key, $value, $expire) {
    // We are already writing to the table, so perform garbage collection at
    // the end of this request.
    $this->needsGarbageCollection = TRUE;
    $result = $this->connection->merge($this->table)
      ->insertFields(array(
        'collection' => $this->collection,
        'name' => $key,
        'value' => serialize($value),
        'expire' => REQUEST_TIME + $expire,
      ))
      ->condition('collection', $this->collection)
      ->condition('name', $key)
      ->execute();
    return $result == \MergeQuery::STATUS_INSERT;
  }

  /**
   * {@inheritdoc}
   */
  function setMultipleWithExpire(array $data, $expire) {
    foreach ($data as $key => $value) {
      $this->setWithExpire($key, $value, $expire);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple(array $keys) {
    // We are already writing to the table, so perform garbage collection at
    // the end of this request.
    $this->needsGarbageCollection = TRUE;
    parent::deleteMultiple($keys);
  }

  /**
   * {@inheritdoc}
   */
  public function destruct() {
    if ($this->needsGarbageCollection) {
      $this->connection->delete($this->table)
        ->condition('expire', REQUEST_TIME, '<')
        ->execute();
    }
  }

}
