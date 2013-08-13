<?php

/**
 * @file
 * Contains FluxEntityMetadataController.
 */

namespace Drupal\fluxservice;

/**
 * Metadata controller base class for remote entities.
 */
class FluxEntityMetadataController {

  /**
   * The entity type.
   *
   * @var string
   */
  protected $entityType;

  /**
   * The entity info array.
   *
   * @var array
   */
  protected $entityInfo;

  /**
   * Constructs the object.
   */
  public function __construct($entity_type) {
    $this->entityType = $entity_type;
    $this->entityInfo = entity_get_info($entity_type);
  }

  /**
   * Implements hook_entity_property_info().
   */
  public function entityPropertyInfo() {
    $info[$this->entityType] = array(
      'properties' => array(),
      'bundles' => array(),
    );

    if (isset($this->entityInfo['entity class']) && $class = $this->entityInfo['entity class']) {
      if (method_exists($class, 'getEntityPropertyInfo')) {
        $info[$this->entityType]['properties'] = $class::getEntityPropertyInfo($this->entityType, $this->entityInfo);
      }
    }

    foreach ($this->entityInfo['bundles'] as $bundle => $bundle_info) {
      if (isset($info['bundle class']) && $class = $bundle_info['bundle class']) {
        if (method_exists($class, 'getBundlePropertyInfo')) {
          $info[$this->entityType]['bundles'][$bundle]['properties'] = $class::getBundlePropertyInfo($this->entityType, $this->entityInfo, $bundle);
        }
      }
    }

    return $info;
  }

}
