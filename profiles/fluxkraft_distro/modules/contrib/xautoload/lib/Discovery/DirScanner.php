<?php
/**
 * This file is autoloaded with the regular uncached xautoload.
 */


/**
 * Searches a directory for files that follow the xautoload naming scheme.
 * Actually, this only works in D6 (yet), but we keep this file around for
 * easier porting between the D6 and the D7 version of xautoload.
 */
class xautoload_Discovery_DirScanner {

  protected $locations;

  function __construct(array &$locations) {
    $this->locations =& $locations;
  }

  function scan($dir, $prefix) {
    foreach (scandir($dir) as $candidate) {
      if ($candidate == '.' || $candidate == '..') {
        continue;
      }
      $path = $dir . '/' . $candidate;
      // TODO: Strict checking for valid identifier strings
      if (preg_match('#^(.+)\.inc$#', $candidate, $m)) {
        if (is_file($path)) {
          $name = $prefix . '_' . $m[1];
          $this->locations[$name] = $path;
        }
      }
      elseif (preg_match('#^(.+)$#', $candidate, $m)) {
        if (is_dir($path)) {
          $this->scan($path, $prefix . '_' . $candidate);
        }
      }
    }
  }
}
