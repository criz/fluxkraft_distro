<?php

class xautoload_MissingDirPlugin_DrupalExtensionPrefix extends xautoload_MissingDirPlugin_DrupalExtensionAbstract {

  function alternativeDir($path_fragment) {
    $extension = substr($path_fragment, 0, -1);
    if ($filepath = drupal_get_filename($this->type, $extension)) {
      if ($this->shallow) {
        return dirname($filepath) . '/lib/';
      }
      else {
        return dirname($filepath) . '/lib/' . $extension . '/';
      }
    }
  }
}
