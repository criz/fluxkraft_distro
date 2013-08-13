<?php


class xautoload_InjectedAPI_hookXautoload {

  protected $finder;
  protected $extensionDir;

  /**
   * @param xautoload_ClassFinder $finder
   *   The class finder.
   */
  function __construct($finder) {
    $this->finder = $finder;
  }

  /**
   * Register an additional namespace for this module.
   * Note: Drupal\<module name>\ is already registered for <module dir>/lib.
   *
   * @param string $namespace
   *   The namespace
   * @param string $psr_0_root_dir
   *   PSR-0 root dir.
   *   If $relative is TRUE, this is relative to the current module dir.
   *   If $relative is FALSE, this is an absolute path.
   * @param boolean $relative
   *   Whether or not the path is relative to the current extension dir.
   */
  function namespaceRoot($namespace, $psr_0_root_dir = NULL, $relative = TRUE) {
    $psr_0_root_dir = $this->processDir($psr_0_root_dir, $relative);
    $this->finder->registerNamespaceRoot($namespace, $psr_0_root_dir);
  }

  /**
   * Register an additional namespace for this module.
   * Note: Drupal\<module name>\ is already registered for <module dir>/lib.
   *
   * @param string $namespace
   *   The namespace
   * @param string $prefix_root_dir
   *   Prefix root dir.
   *   If $relative is TRUE, this is relative to the extension module dir.
   *   If $relative is FALSE, this is an absolute path.
   * @param boolean $relative
   *   Whether or not the path is relative to the current extension dir.
   */
  function prefixRoot($prefix, $prefix_root_dir = NULL, $relative = TRUE) {
    $prefix_root_dir = $this->processDir($prefix_root_dir, $relative);
    $this->finder->registerPrefixRoot($prefix, $prefix_root_dir);
  }

  /**
   * Register an additional namespace for this module.
   * Note: Drupal\<module name>\ is already registered for <module dir>/lib.
   *
   * @param string $namespace
   *   The namespace
   * @param string $psr_0_root_dir
   *   PSR-0 root dir.
   *   If $relative is TRUE, this is relative to the current extension dir.
   *   If $relative is FALSE, this is an absolute path.
   * @param boolean $relative
   *   Whether or not the path is relative to the current extension dir.
   */
  function namespaceDeep($namespace, $namespace_deep_dir = NULL, $relative = TRUE) {
    $namespace_deep_dir = $this->processDir($namespace_deep_dir, $relative);
    $this->finder->registerNamespaceDeep($namespace, $namespace_deep_dir);
  }

  /**
   * Register an additional namespace for this module.
   * Note: Drupal\<module name>\ is already registered for <module dir>/lib.
   *
   * @param string $namespace
   *   The namespace
   * @param string $prefix_deep_dir
   *   PSR-0 root dir.
   *   If $relative is TRUE, this is relative to the current extension dir.
   *   If $relative is FALSE, this is an absolute path.
   * @param boolean $relative
   *   Whether or not the path is relative to the current extension dir.
   */
  function prefixDeep($prefix, $prefix_deep_dir = NULL, $relative = TRUE) {
    $prefix_root_dir = $this->processDir($prefix_deep_dir, $relative);
    $this->finder->registerPrefixDeep($prefix, $prefix_deep_dir);
  }

  /**
   * Legacy: Plugins were called "Handler" before.
   */
  function namespaceHandler($namespace, $plugin) {
    $this->finder->registerNamespacePlugin($namespace, $plugin);
  }

  /**
   * Legacy: Plugins were called "Handler" before.
   */
  function prefixHandler($prefix, $plugin) {
    $this->finder->registerPrefixPlugin($prefix, $plugin);
  }

  /**
   * Register a namespace plugin object
   */
  function namespacePlugin($namespace, $plugin) {
    $this->finder->registerNamespacePlugin($namespace, $plugin);
  }

  /**
   * Register a prefix plugin object
   */
  function prefixPlugin($prefix, $plugin) {
    $this->finder->registerPrefixPlugin($prefix, $plugin);
  }

  /**
   * Process a given directory to make it relative to Drupal root,
   * instead of relative to the current extension dir.
   */
  protected function processDir($dir, $relative) {
    if (!isset($dir)) {
      $dir = $this->extensionDir . '/lib';
    }
    elseif ($relative) {
      // Root dir is relative to module root.
      if (empty($dir)) {
        $dir = $this->extensionDir;
      }
      else {
        $dir = $this->extensionDir . '/' . $dir;
      }
    }
    else {
      // Leave the $dir as it is.
    }
    return $dir;
  }

  /**
   * Set a module to use as base for relative paths.
   */
  function setModule($module) {
    $this->extensionDir = drupal_get_path('module', $module);
  }

  /**
   * Set a theme to use as base for relative paths.
   */
  function setTheme($theme) {
    $this->extensionDir = drupal_get_path('theme', $theme);
  }

  /**
   * Set a library to use as base for relative paths.
   */
  function setLibrary($library) {
    if (!module_exists('libraries')) {
      throw new Exception('Libraries module not installed.');
    }
    $this->extensionDir = libraries_get_path($library);
  }

  /**
   * Explicitly set the base for relative paths.
   */
  function setExtensionDir($dir) {
    $this->extensionDir = $dir;
  }
}
