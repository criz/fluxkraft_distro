<?php

/**
 * @file
 * Contains DatabaseStorage.
 */

namespace Drupal\fluxservice\KeyValueStore;

/**
 * Defines a default key/value store implementation.
 *
 * This is Drupal's default key/value store implementation. It uses the database
 * to store key/value data.
 */
class DatabaseStorage extends StorageBase {

  /**
   * The database connection.
   *
   * @var \DatabaseConnection
   */
  protected $connection;

  /**
   * The name of the SQL table to use.
   *
   * @var string
   */
  protected $table;

  /**
   * Constructs a DatabaseStorage object.
   */
  public function __construct($collection, \DatabaseConnection $connection, $table = 'fluxservice_key_value') {
    parent::__construct($collection);
    $this->connection = $connection;
    $this->table = $table;
  }

  /**
   * {@inheritdoc}
   */
  public function getMultiple(array $keys) {
    $values = array();
    try {
      $result = $this->connection->query('SELECT name, value FROM {' . $this->connection->escapeTable($this->table) . '} WHERE name IN (:keys) AND collection = :collection', array(':keys' => $keys, ':collection' => $this->collection))->fetchAllAssoc('name');
      foreach ($keys as $key) {
        if (isset($result[$key])) {
          $values[$key] = unserialize($result[$key]->value);
        }
      }
    }
    catch (\Exception $e) {
      // @todo: Perhaps if the database is never going to be available,
      // key/value requests should return FALSE in order to allow exception
      // handling to occur but for now, keep it an array, always.
    }
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function getAll() {
    $result = $this->connection->query('SELECT name, value FROM {' . $this->connection->escapeTable($this->table) . '} WHERE collection = :collection', array(':collection' => $this->collection));
    $values = array();

    foreach ($result as $item) {
      if ($item) {
        $values[$item->name] = unserialize($item->value);
      }
    }
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function set($key, $value) {
    $this->connection->merge($this->table)
      ->key(array(
        'name' => $key,
        'collection' => $this->collection,
      ))
      ->fields(array('value' => serialize($value)))
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function setIfNotExists($key, $value) {
    $result = $this->connection->merge($this->table)
      ->insertFields(array(
        'collection' => $this->collection,
        'name' => $key,
        'value' => serialize($value),
      ))
      ->condition('collection', $this->collection)
      ->condition('name', $key)
      ->execute();
    return $result == \MergeQuery::STATUS_INSERT;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteMultiple(array $keys) {
    // Delete in chunks when a large array is passed.
    do {
      $this->connection->delete($this->table)
        ->condition('name', array_splice($keys, 0, 1000))
        ->condition('collection', $this->collection)
        ->execute();
    }
    while (count($keys));
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAll() {
    $this->connection->delete($this->table)
      ->condition('collection', $this->collection)
      ->execute();
  }
}
