<?php


/**
 * Helper class for the class finder.
 * This is not part of ClassFinder, because we want to use the same logic for
 * namespaces (PSR-0) and prefixes (PEAR).
 *
 * This thing does not actually deal with class names, but with transformed
 * paths.
 *
 * Example A:
 * When looking for a class \Aaa\Bbb\Ccc_Ddd, the class finder will
 * 1. Determine that this class is within a namespace.
 * 2. Transform that into "Aaa/Bbb/Ccc/Ddd.php".
 * 3. Check if the namespace map evaluator has anything registered for
 *   3.1. "Aaa/Bbb/"
 *   3.2. "Aaa/"
 *   3.3. ""
 *
 * Example A:
 * When looking for a class Aaa_Bbb_Ccc, the class finder will
 * 1. Determine that this class is NOT within a namespace.
 * 2. Check if a file is explicitly registered for the class itself.
 * 3. Transform the class name into "Aaa/Bbb/Ccc.php".
 * 4. Check if the prefix map evaluator has anything registered for
 *   4.1. "Aaa/Bbb/"
 *   4.2. "Aaa/"
 *   4.3. ""
 */
class xautoload_ClassFinder_Helper_Map {

  protected $paths = array();
  protected $plugins = array();

  // Index of the last inserted plugin.
  // We can't use count(), because plugins at some index can be unset.
  protected $lastPluginIds = array();

  /**
   * If a class file would be in
   *   $psr0_root . '/' . $path_fragment . $path_suffix
   * then instead, we look in
   *   $root_path . '/' . $path_fragment . $path_suffix
   *
   * @param string $path_fragment
   *   The would-be namespace path relative to PSR-0 root.
   *   That is, the namespace with '\\' replaced by DIRECTORY_SEPARATOR.
   * @param string $path
   *   The filesystem location of the (PSR-0) root folder for the given
   *   namespace.
   * @param boolean $lazy_check
   *   If TRUE, then it is yet unknown whether the directory exists. If during
   *   the process we find that it does not exist, we unregister it.
   */
  function registerRootPath($path_fragment, $root_path) {
    $deep_path = $root_path . DIRECTORY_SEPARATOR . $path_fragment;
    $this->registerDeepPath($path_fragment, $deep_path);
  }

  /**
   * If a class file would be in
   *   $psr0_root . '/' . $path_fragment . $path_suffix
   * then instead, we look in
   *   $deep_path . $path_suffix
   *
   * @param string $path_fragment
   *   The would-be namespace path relative to PSR-0 root.
   *   That is, the namespace with '\\' replaced by DIRECTORY_SEPARATOR.
   * @param string $path
   *   The filesystem location of the (PSR-0) subfolder for the given namespace.
   * @param boolean $lazy_check
   *   If TRUE, then it is yet unknown whether the directory exists. If during
   *   the process we find that it does not exist, we unregister it.
   */
  function registerDeepPath($path_fragment, $deep_path, $lazy_check = TRUE) {
    $this->paths[$path_fragment][$deep_path] = $lazy_check;
  }

  /**
   * Register a bunch of those paths ..
   */
  function registerDeepPaths($map) {
    foreach ($map as $key => $paths) {
      if (isset($this->paths[$key])) {
        $paths += $this->paths[$key];
      }
      $this->paths[$key] = $paths;
    }
  }

  /**
   * Register a plugin for a namespace or prefix.
   *
   * @param string $path_fragment
   *   First part of the path generated from the class name.
   * @param xautoload_FinderPlugin_Interface $plugin
   *   The plugin.
   */
  function registerPlugin($path_fragment, $plugin) {

    if (!isset($plugin)) {
      throw new Exception("Second argument cannot be NULL.");
    }
    elseif (!is_a($plugin, 'xautoload_FinderPlugin_Interface')) {
      throw new Exception("Second argument must implement xautoload_FinderPlugin_Interface.");
    }

    if (!isset($this->plugins[$path_fragment])) {
      $id = $this->lastPluginIds[$path_fragment] = 1;
    }
    else {
      $id = ++$this->lastPluginIds[$path_fragment];
    }
    $this->plugins[$path_fragment][$id] = $plugin;

    if (method_exists($plugin, 'setKillswitch')) {
      // Give the plugin a red button to unregister or replace itself.
      $plugin->setKillswitch($plugin, $path_fragment, $id);
    }

    return $id;
  }

  /**
   * Find the file for a class that in PSR-0 or PEAR would be in
   * $psr_0_root . '/' . $path_fragment . $path_suffix
   *
   * @param string $path_fragment
   *   First part of the canonical path, with trailing DIRECTORY_SEPARATOR.
   * @param string $path_suffix
   *   Second part of the canonical path, ending with '.php'.
   */
  function findFile_map($api, $path_fragment, $path_suffix) {
    $path = $path_fragment . $path_suffix;
    while (TRUE) {
      if (isset($this->paths[$path_fragment])) {
        $lazy_remove = FALSE;
        foreach ($this->paths[$path_fragment] as $dir => &$lazy_check) {
          $file = $dir . $path_suffix;
          if ($api->suggestFile($file)) {
            // Next time we can skip the check, because now we know that the
            // directory exists.
            $lazy_check = FALSE;
            return TRUE;
          }
          // Now we know the file does not exist. Does the directory?
          if ($lazy_check) {
            // Lazy-check whether the registered directory exists.
            if ($api->is_dir($dir)) {
              // Next time we can skip the check, because now we know that the
              // directory exists.
              $lazy_check = FALSE;
            }
            else {
              // The registered directory does not exist, so we can unregister it.
              unset($this->paths[$path_fragment][$dir]);
              $lazy_remove = TRUE;
              if (is_object($lazy_check)) {
                $new_dir = $lazy_check->alternativeDir($path_fragment);
                if ($new_dir !== $dir) {
                  $file = $new_dir . $path_suffix;
                  if ($api->suggestFile($file)) {
                    $this->paths[$path_fragment][$new_dir] = FALSE;
                    return TRUE;
                  }
                  elseif ($api->is_dir($new_dir)) {
                    $this->paths[$path_fragment][$new_dir] = FALSE;
                  }
                }
              }
            }
          }
        }
        if ($lazy_remove && empty($this->paths[$path_fragment])) {
          unset($this->paths[$path_fragment]);
        }
      }

      // Check any plugin registered for this fragment.
      if (isset($this->plugins[$path_fragment])) {
        foreach ($this->plugins[$path_fragment] as $plugin) {
          if ($plugin->findFile($api, $path_fragment, $path_suffix)) {
            return TRUE;
          }
        }
      }

      // Continue with parent fragment.
      if ('' === $path_fragment) {
        break;
      }
      elseif (DIRECTORY_SEPARATOR === $path_fragment) {
        // This happens if a class begins with an underscore.
        $path_fragment = '';
        $path_suffix = $path;
      }
      elseif (FALSE !== $pos = strrpos($path_fragment, DIRECTORY_SEPARATOR, -2)) {
        $path_fragment = substr($path_fragment, 0, $pos + 1);
        $path_suffix = substr($path, $pos + 1);
      }
      else {
        $path_fragment = '';
        $path_suffix = $path;
      }
    }
  }
}
