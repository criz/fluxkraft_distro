<?php

/**
 * @file
 * Contains AccountMetadataController.
 */

namespace Drupal\fluxservice;

/**
 * Metadata controller class for service instances.
 */
class ServiceMetadataController extends \EntityDefaultMetadataController {

  /**
   * {@inheritdoc}
   */
  public function entityPropertyInfo() {
    $info = parent::entityPropertyInfo();
    $properties = &$info[$this->type]['properties'];

    // Set the machine-readable name to type 'text' (assumes 'token' otherwise).
    if (!empty($this->info['entity keys']['name']) && $key = $this->info['entity keys']['name']) {
      $properties[$key]['type'] = 'text';
    }

    // Add an options list callback for the plugin property.
    $properties['plugin']['options list'] = 'fluxservice_service_plugin_options';

    // Append bundle (plugin) specific properties.
    foreach (fluxservice_get_service_plugin_info() as $plugin => $plugin_info) {
      if ($definitions = $plugin_info['class']::getBundlePropertyInfo($this->type, $this->info, $plugin)) {
        $info[$this->type]['bundles'][$plugin]['properties'] = $definitions;
      }
    }

    // The 'uid' property refers to the 'user' entity type.
    $properties['uid']['type'] = 'user';

    return $info;
  }

}
