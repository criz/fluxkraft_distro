<?php

/**
 * @file
 * Contains EntityInterface.
 */

namespace Drupal\fluxservice\Entity;

/**
 * Extended entity interface for fluxservice and integrationg modules.
 *
 * This interface must be implemented by entity types provided via the
 * fluxservice plugin mechanism. In order to be discovered plugin implementation
 * classes must reside in the "Entity" directory below a directory declared via
 * hook_fluxservice_plugin_directory() and implement a static getInfo() method
 * returning an array including the following information:
 *  - name: The machine name of the entity type.
 *  - All other keys as support by hook_entity_info(), see hook_entity_info()
 *    and entity_crud_hook_entity_info() for details.
 *
 * See \Drupal\fluxtwitter\Plugin\Entity\TwitterTweet of the fluxtwitter
 * module for an example.
 */
interface EntityInterface {

  /**
   * Returns whether the entity is new.
   *
   * Usually an entity is new if no ID exists for it yet. However, entities may
   * be enforced to be new with existing IDs too.
   *
   * @return
   *   TRUE if the entity is new, or FALSE if the entity has already been saved.
   */
  public function isNew();

  /**
   * Enforces an entity to be new.
   *
   * Allows migrations to create entities with pre-defined IDs by forcing the
   * entity to be new before saving.
   *
   * @param bool $value
   *   (optional) Whether the entity should be forced to be new. Defaults to
   *   TRUE.
   */
  public function enforceIsNew($value = TRUE);

  /**
   * @return mixed
   */
  public function bundle();

  public function identifier();

  public function label();

  public function save();

  public function delete();

}
