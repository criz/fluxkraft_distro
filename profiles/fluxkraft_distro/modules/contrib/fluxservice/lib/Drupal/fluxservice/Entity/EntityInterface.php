<?php

/**
 * @file
 * Contains EntityInterface.
 */

namespace Drupal\fluxservice\Entity;

/**
 * Extended entity interface for fluxservice and integration modules.
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
   * Instantiates a new entity object based on the given values.
   *
   * @param array $values
   *   The property values of the entity.
   * @param string $entity_type
   *   The entity type to create.
   * @param $entity_info
   *   The info of the entity type.
   *
   * @return self
   *   An instantiated entity object.
   */
  public static function factory(array $values, $entity_type, $entity_info);

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
   * Returns the bundle of the entity.
   *
   * @return
   *   The bundle of the entity. Defaults to the entity type if the entity type
   *   does not make use of different bundles.
   */
  public function bundle();

  /**
   * Returns the entity identifier, i.e. the entities name or numeric id.
   *
   * @return
   *   The identifier of the entity. If the entity type makes use of a name key,
   *   the name is returned, else the numeric id.
   *
   * @see entity_id()
   */
  public function identifier();

  /**
   * Returns the label of the entity.
   *
   * Modules may alter the label by specifying another 'label callback' using
   * hook_entity_info_alter().
   *
   * @see entity_label()
   */
  public function label();

  /**
   * Permanently saves the entity.
   *
   * @see entity_save()
   */
  public function save();

  /**
   * Permanently deletes the entity.
   *
   * @see entity_delete()
   */
  public function delete();

  /**
   * Describes entity properties.
   *
   * Properties that are available for all bundles of an entity type should be
   * described here, bundle-specific properties in getBundlePropertyInfo().
   *
   * @param string $entity_type
   *   The entity type.
   * @param array $entity_info
   *   The entity info of the given entity type.
   *
   * @return array
   *   An array describing entity properties.
   *
   * @see hook_entity_property_info()
   * @see FluxEntityMetadataController
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info);

  /**
   * Describes bundle specific properties.
   *
   * @param string $entity_type
   *   The entity type.
   * @param array $entity_info
   *   The entity info of the given entity type.
   * @param string $bundle
   *   The name of the bundle, for which properties should be described.
   *
   * @return array
   *   An array describing entity properties of the given bundle.
   *
   * @see hook_entity_property_info()
   * @see FluxEntityMetadataController
   */
  public static function getBundlePropertyInfo($entity_type, $entity_info, $bundle);

}
