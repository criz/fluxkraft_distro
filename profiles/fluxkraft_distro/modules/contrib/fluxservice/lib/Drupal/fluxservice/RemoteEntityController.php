<?php

/**
 * @file
 * Contains RemoteEntityController.
 */

namespace Drupal\fluxservice;

use Drupal\fluxservice\Entity\FluxEntityInterface;
use Drupal\fluxservice\Entity\RemoteEntityInterface;

/**
 * Default remote entity controller base.
 */
abstract class RemoteEntityController extends \EntityAPIController implements RemoteEntityControllerInterface {

  /**
   * The character used for separating the different parts of the entity id.
   *
   * @var string
   */
  protected $separator = ':';

  /**
   * The key of the remote identifier property.
   *
   * @var string
   */
  protected $remoteIdKey;

  /**
   * Base constructor for constructing RemoteEntityController objects.
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
    if (!empty($conditions)) {
      return array();
    }

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

    // Load any remaining entities from the database. This is the case if $ids
    // is set to FALSE (so we load all entities), if there are any ids left to
    // load or if loading a revision.
    if (!($this->cacheComplete && $ids === FALSE) && ($ids === FALSE || !empty($ids))) {
      // Skip entities already retrieved from cache.
      if ($queried = array_diff_key($this->query($ids, array(), FALSE), $entities)) {
        // Pass all entities loaded from the database through $this->attachLoad(),
        // which attaches fields (if supported by the entity type) and calls the
        // entity type specific load callback, for example hook_node_load().
        $this->attachLoad($queried);
        $entities += $queried;

        if (!empty($this->cache)) {
          $this->cacheSet($queried);
          // Remember if we have cached all entities now.
          $this->cacheComplete = $ids === FALSE ? TRUE : $this->cacheComplete;
        }
      }
    }

    // Ensure that the returned array is ordered the same as the original.
    if (!empty($passed) && $passed = array_intersect_key($passed, $entities)) {
      foreach ($passed as $id => $value) {
        $passed[$id] = $entities[$id];
      }
      $entities = $passed;
    }

    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function query($ids, $conditions, $revision_id = FALSE) {
    // Decode the ids and group them by agents.
    $agents = $groups = array();
    foreach ($ids as $id) {
      list($type, $agent, $identifier) = explode($this->separator, $id, 3);
      if (!in_array($type, array('account', 'service'), TRUE)) {
        throw new \InvalidArgumentException('The agent has to be an account or a service entity.');
      }

      $agent = entity_load_single("fluxservice_$type", $agent);
      $group = $agent->identifier();
      $agents[$group] = $agent;
      $groups[$group][$identifier] = $identifier;
    }

    // Load each group separately.
    $entities = array();
    foreach ($groups as $group => $identifiers) {
      foreach ($this->loadFromService($identifiers, $agents[$group]) as $item) {
        $entity = $this->entifyItem($item, $agents[$group]);
        $entities[$entity->identifier()] = $entity;
      }
    }

    return $entities;
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
  public function entifyBycatch(array $items, FluxEntityInterface $agent) {
    // First, entify the items.
    $entities = array();
    foreach ($items as $item) {
      $entity = $this->entifyItem($item, $agent);
      $entities[$entity->identifier()] = $entity;
    }

    // Skip adding already present entities.
    if ($cached = $this->cacheGet(array_keys($entities))) {
      $entities = array_diff_key($entities, $cached);
    }

    // Invoke the load hooks.
    $this->attachLoad($entities);
    $this->cacheSet($entities);

    return $entities + $cached;
  }

  /**
   * Converts a remote entity array into a proper entity object.
   *
   * @param array $item
   *   The entity values as an array.
   * @param FluxEntityInterface $agent
   *   The agent associated with the item.
   *
   * @throws \InvalidArgumentException
   *   If the given agent is not a service or account entity.
   *
   * @return RemoteEntityInterface
   *   A remote entity object.
   */
  protected function entifyItem(array $item, FluxEntityInterface $agent) {
    // The output array has to be keyed by the id of the contained entity.
    $class = $this->entityInfo['entity class'];
    $entity = $class::factory($item, $this->entityType, $this->entityInfo);

    if (is_subclass_of($agent, 'Drupal\fluxservice\Plugin\Entity\AccountInterface')) {
      $type = 'account';
      $entity->setAccount($agent);
    }
    elseif (is_subclass_of($agent, 'Drupal\fluxservice\Plugin\Entity\ServiceInterface')) {
      $type = 'service';
      $entity->setService($agent);
    }
    else {
      throw new \InvalidArgumentException('The agent has to be an account or a service entity.');
    }

    // Response values may differ based on which account was used for the
    // request, hence we have to concatenate the account and response object
    // id for building a unique entity id for the remote object.
    $entity->{$this->idKey} = "$type:{$agent->identifier()}{$this->separator}{$entity->getRemoteIdentifier()}";

    return $entity;
  }

  /**
   * Loads remote items via the web service.
   *
   * @param array $ids
   *   An array of remote ids.
   * @param FluxEntityInterface $agent
   *   The agent for which to load the entities.
   *
   * @return array
   *   An array of loaded items.
   */
  abstract protected function loadFromService($ids, FluxEntityInterface $agent);

  /**
   * Sends an entity to the web service.
   *
   * @param RemoteEntityInterface $entity
   *   The entity to be saved.
   */
  abstract protected function sendToService(RemoteEntityInterface $entity);

}
