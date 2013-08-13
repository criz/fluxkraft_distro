<?php

/**
 * You can include this file as early in the request as you wish,
 * e.g. from your site's settings.php, to make the class loader available for
 * external libraries during bootstrap.
 *
 * This will *not* register any Drupal modules, because information about these
 * might not be available yet.
 */

define('XAUTOLOAD_LIB_DIR', dirname(__FILE__) . '/lib');

_xautoload_register();


// Public API functions.
// -----------------------------------------------------------------------------

/**
 * Get the class finder object.
 * This is the public version of _xautoload_finder().
 */
function xautoload_get_finder() {
  // Get it from the registry.
  return xautoload('classFinder');
}

/**
 * Get a service object from the registry.
 * Services are lazy-created first time you need them.
 *
 * @param string $key
 *   Identifier of the service within the registry.
 *   The xautoload_ServiceFactory should have a method with the same name.
 */
function xautoload($key = 'main') {
  static $service_registry;
  if (!isset($service_registry)) {
    $service_factory = new xautoload_ServiceFactory();
    $service_registry = new xautoload_Container_LazyServices($service_factory);
  }
  return $service_registry->get($key);
}


// "Private" functions.
// -----------------------------------------------------------------------------

/**
 * Create and register the xautoload class loader.
 * Register the xautoload prefix, but don't register any Drupal-specific stuff yet.
 */
function _xautoload_register() {

  // Check that this runs only once.
  static $_first_run = TRUE;
  if (!$_first_run) {
    return;
  }
  $_first_run = FALSE;

  // Register a temporary loader.
  spl_autoload_register('_xautoload_autoload_temp');

  // This one class needs to be loaded manually.
  // Just believe me on that one.
  _xautoload_autoload_temp('xautoload_InjectedAPI_findFile');

  // Register the "real" class loader.
  xautoload('loaderManager')->register(NULL);

  // Register the xautoload_ prefix.
  xautoload('finder')->registerPrefixDeep('xautoload', XAUTOLOAD_LIB_DIR);

  // Unregister our temporary loader.
  spl_autoload_unregister('_xautoload_autoload_temp');
}

/**
 * Temporary loader callback, to avoid any module_load_include()
 * while building the real autoloader.
 *
 * @param string $name
 *   Name of the class or interface we want to load.
 */
function _xautoload_autoload_temp($name) {

  if (preg_match('#^xautoload_(.*)$#', $name, $m)) {
    // This is boot time, drupal_get_path() is not available yet.
    $file = XAUTOLOAD_LIB_DIR . '/' . strtr($m[1], '_', '/') . '.php';
    require_once $file;
    if (!class_exists($name, FALSE) && !interface_exists($name, FALSE)) {
      throw new Exception("Class $name not found in $file.");
    }
  }
}
