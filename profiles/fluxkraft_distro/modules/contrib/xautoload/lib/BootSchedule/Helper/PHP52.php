<?php

class xautoload_BootSchedule_Helper_PHP52 extends xautoload_BootSchedule_Helper_Base {

  /**
   * Register prefixes for enabled Drupal extensions (modules/themes).
   *
   * @param array $extensions
   *   Info about extensions.
   */
  function registerExtensions($extensions) {
    $prefix_maps = array();
    foreach ($extensions as $info) {
      $prefix_maps[$info->type][$info->name] = dirname($info->filename) . '/lib';
    }
    $this->registerPrefixMaps($prefix_maps);
  }
}
