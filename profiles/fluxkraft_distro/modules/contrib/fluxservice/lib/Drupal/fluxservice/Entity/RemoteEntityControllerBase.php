<?php

/**
 * @file
 * Contains RemoteEntityControllerBase.
 */

namespace Drupal\fluxservice\Entity;

use Drupal\fluxservice\Exception;
use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Default remote entity controller base.
 */
abstract class RemoteEntityControllerBase extends \EntityAPIController implements RemoteEntityControllerInterface {

  /**
   * The key of the remote identifier property.
   *
   * @var string
   */
  protected $remoteIdKey;

  /**
   * Base constructor for constructing RemoteEntityControllerBase objects.
   */
  public function __construct($entity_type) {
    parent::__construct($entity_type);

    // Write some properties for convenience.
    $this->remoteIdKey = $this->entityInfo['entity keys']['remote id'];
  }

  /**
   * {@inheritdoc}
   */
  public function load($ids = array(), $conditions = array()) {
    // We do not support loading by conditions and IDs for now.
    if ($ids !== FALSE && !empty($conditions)) {
      return array();
    }
    elseif ($conditions) {
      return $this->loadByProperties($conditions);
    }

    try {
      return $this->doLoad($ids);
    }
    catch (\Exception $exception) {
      watchdog_exception($this->entityType, $exception);
      throw $exception;
    }
  }

  /**
   * The usual entity loading method, without conditions support.
   */
  protected function doLoad($ids = array()) {

    $entities = array();
    // Create a new variable which is either a prepared version of the $ids
    // array for later comparison with the entity cache, or FALSE if no $ids
    // were passed. The $ids array is reduced as items are loaded from cache,
    // and we need to know if it's empty for this reason to avoid querying the
    // database when all requested entities are loaded from cache.
    $passed = !empty($ids) ? array_flip($ids) : FALSE;

    // Try to load entities from the static cache.
    if (!empty($this->cache)) {
      $entities = $this->cacheGet($ids);
      // If any entities were loaded, remove them from the ids still to load.
      if (!empty($passed)) {
        $ids = array_keys(array_diff_key($passed, $entities));
      }
    }

    // Support the entitycache module if activated.
    if (!empty($this->entityInfo['entity cache']) && $ids) {
      $cached_entities = \EntityCacheControllerHelper::entityCacheGet($this, $ids);
      // If any entities were loaded, remove them from the ids still to load.
      $ids = array_diff($ids, array_keys($cached_entities));
      $entities += $cached_entities;

      // Add loaded entities to the static cache.
      if ($this->cache && !empty($cached_entities)) {
        $this->cacheSet($cached_entities);
      }
    }

    // Load any remaining entities from the database. This is the case if $ids
    // is set to FALSE (so we load all entities), if there are any ids left to
    // load or if loading a revision.
    if (!($this->cacheComplete && $ids === FALSE) && ($ids === FALSE || !empty($ids))) {
      // Skip entities already retrieved from cache.
      if ($queried = array_diff_key($this->query($ids, array(), FALSE), $entities)) {
        // Only run attach-load on entities that could be loaded.
        $queried_loaded = array_filter($queried);
        $this->attachLoad($queried_loaded);
        $entities += $queried_loaded;
        // Make sure any potential changes to $queries_loaded are reflected.
        $queried = $queried_loaded + $queried;

        if (!empty($this->cache)) {
          $this->cacheSet($queried);
          // Remember if we have cached all entities now.
          $this->cacheComplete = $ids === FALSE ? TRUE : $this->cacheComplete;
        }
        // Entitycache module support: Add entities to the entity cache.
        if (!empty($this->entityInfo['entity cache'])) {
          \EntityCacheControllerHelper::entityCacheSet($this, $queried);
        }
      }
    }

    // Ensure that the returned array is ordered the same as the original and
    // remove any entities which have been bycatched during load.
    if (!empty($passed)) {
      $passed = array_intersect_key($passed, $entities);
      foreach ($passed as $id => $value) {
        $passed[$id] = $entities[$id];
      }
      $entities = $passed;
    }

    // Filter out FALSE values for not-loadable entities.
    return array_filter($entities);
  }

  /**
   * Load entities by their property values.
   *
   * @param array $values
   *   An associative array where the keys are the property names and the
   *   values are the values those properties must have.
   *
   * @return array
   *   An array of entity objects indexed by their ids.
   */
  public function loadByProperties(array $values = array()) {
    // Not implemented by default. Implement, or use EFQ instead and integrate
    // it here.
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function create(array $values = array()) {
    $class = $this->entityInfo['entity class'];
    return $class::factory($values, $this->entityType, $this->entityInfo);
  }

  /**
   * {@inheritdoc}
   */
  public function save($entity) {
    try {
      $this->invoke('presave', $entity);

      // Send the entity to the web service.
      $this->sendToService($entity);

      if ($entity->isNew()) {
        $this->invoke('insert', $entity);
        $return = SAVED_NEW;
      }
      else {
        $this->resetCache(array($entity->identifier()));
        $this->invoke('update', $entity);
        $return = SAVED_UPDATED;
      }

      $entity->enforceIsNew(FALSE);

      return $return;
    }
    catch (\Exception $exception) {
      watchdog_exception($this->entityType, $exception);
      throw $exception;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function delete($ids) {
    $entities = $ids ? $this->load($ids) : FALSE;
    if (!$entities) {
      // Do nothing, in case invalid or no ids have been passed.
      return;
    }

    try {
      // Delete the entity via the service.
      foreach ($entities as $entity) {
        $this->deleteFromService($entity);
      }
      // Reset the cache as soon as the changes have been applied.
      $this->resetCache($ids);

      foreach ($entities as $id => $entity) {
        $this->invoke('delete', $entity);
      }
    }
    catch (Exception $e) {
      watchdog_exception($this->entityType, $e);
      throw $e;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function bycatch(array $items, ServiceInterface $service, AccountInterface $account = NULL) {
    // Turn bycatched items into cached entities.
    $entities = $this->entify($items, $service, $account);

    // Invoke the load hooks and cache entities. Note that we re-new caches
    // for possible already cached entity objects also.
    $this->attachLoad($entities);
    $this->cacheSet($entities);
    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function entify(array $items, ServiceInterface $service, AccountInterface $account = NULL) {
    // Give subclasses a chance to easily implement custom entify logic.
    $this->preEntify($items, $service, $account);

    $entities = array();
    foreach ($items as $remote_id => $item) {
      // Response values may differ based on which account was used for the
      // request, hence we have to concatenate the account and response object
      // id for building a unique entity id for the remote object.
      $entity_id = $this->buildDrupalEntityId($remote_id, $service, $account);

      if ($item) {
        // Make sure the entity-id gets set on the entity object.
        $item[$this->idKey] = $entity_id;

        // Create the entity object.
        $class = $this->entityInfo['entity class'];
        $entity = $class::factory($item, $this->entityType, $this->entityInfo);
        $entity->setService($service);
        if (isset($account)) {
          $entity->setAccount($account);
        }
      }
      // Handle not loadable, e.g. not more existing entities.
      else {
        $entity = FALSE;
      }
      $entities[$entity_id] = $entity;
    }

    return $entities;
  }

  /**
   * Gives sub-classes a chance to easily implement custom entify logic.
   *
   * @param array $items
   *   The array of items about to be entified.
   * @param \Drupal\fluxservice\Plugin\Entity\ServiceInterface $service
   *   The service endpoint used to load the entities.
   * @param \Drupal\fluxservice\Plugin\Entity\AccountInterface $account
   *   (optional) The service account used to load the entities, if any.
   */
  protected function preEntify(array &$items, ServiceInterface $service, AccountInterface $account = NULL) {
    // Since some classes might not need this, we won't enforce this method by
    // making it abstract.
  }

  /**
   * Sends an entity to the remote service.
   *
   * @param \Drupal\fluxservice\Entity\RemoteEntityInterface $entity
   *   The entity to be saved.
   *
   * @throws Exception
   *   If the entity is (implemented) read-only.
   */
  protected function sendToService(RemoteEntityInterface $entity) {
    throw new \Exception("The entity type {$this->entityType} does not support writing.");
  }

  /**
   * Deletes an entity from the remote service.
   *
   * @param \Drupal\fluxservice\Entity\RemoteEntityInterface $entity
   *   The entity to be deleted.
   *
   * @throws Exception
   *   If the entity is (implemented) read-only.
   */
  protected function deleteFromService(RemoteEntityInterface $entity) {
    throw new \Exception("The entity type {$this->entityType} does not support deleting.");
  }

}
