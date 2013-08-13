<?php

/**
 * @file
 * Contains AccountMetadataController.
 */

namespace Drupal\fluxservice;

/**
 * Metadata controller class for personal service accounts.
 */
class AccountMetadataController extends \EntityDefaultMetadataController {

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
    $properties['plugin']['options list'] = 'fluxservice_account_plugin_options';

    // Append bundle (plugin) specific properties.
    foreach (fluxservice_get_account_plugin_info() as $plugin => $plugin_info) {
      if ($definitions = $plugin_info['class']::getBundlePropertyInfo()) {
        $info[$this->type]['bundles'][$plugin]['properties'] = $definitions;
      }
    }

    // The 'service' property refers to the 'fluxservice_service' entity type.
    $properties['service']['type'] = 'fluxservice_service';

    // The 'uid' property refers to the 'user' entity type.
    $properties['uid']['type'] = 'user';

    return $info;
  }

}
