<?php

/**
 * @file
 * Contains the fluxservice plugin system.
 */

/**
 * Retrieves all available plugin definitions for a given plugin type.
 *
 * @param $type
 *   The plugin type.
 * @param $plugin
 *   (optional) The machine-readable name of a plugin definition.
 *
 * @return array|null
 *   An array of plugin definitions keyed by their machine-readable name or a
 *   single plugin definition array if $plugin was given and a matching record
 *   could be found. Returns NULL if $plugin was given and there was no matching
 *   plugin definition.
 *
 * @see fluxservice_plugin_info_cache_clear()
 */
function fluxservice_get_plugin_info($type, $plugin = NULL) {
  // Try to retrieve the plugin definitions from the static cache.
  $info = &drupal_static("fluxservice:plugins");

  // Try to retrieve the plugin definitions from the persistent cache if they
  // could not be retrieved from the static cache.
  if (!isset($info[$type]) && ($cache = cache_get("fluxservice:plugins:$type")) !== FALSE) {
    $info[$type] = $cache->data;
  }

  // If the plugin definitions could not be retrieved from the cache either we
  // need to (re-)discover them from the info hook implementations.
  if (!isset($info[$type])) {
    $info[$type] = fluxservice_discover_plugins($type);

    // Cache the plugin definitions for future requests.
    cache_set("fluxservice:plugins:$type", $info[$type]);
  }

  // Either return the full array of plugin definitions or just a single record.
  if (!isset($plugin)) {
    return $info[$type];
  }
  elseif (isset($info[$type][$plugin])) {
    return $info[$type][$plugin];
  }
}

/**
 * Discovers all available plugin definitions for a given plugin type.
 *
 * @param $type
 *   The plugin type.
 *
 * @return array
 *   An array of plugin definitions keyed by the machine-readable name.
 */
function fluxservice_discover_plugins($type) {
  $types = module_invoke_all('fluxservice_plugin_type_info');
  $type_info = $types[$type];
  $interface = $type_info['interface'];

  // Make sure all files possibly holding plugins are included.
  $files = array();
  foreach (fluxservice_get_plugin_directories() as $module => $directory) {
    $path = drupal_get_path('module', $module) . "/$directory/{$type_info['directory']}/*.{inc,php}";
    $path = DIRECTORY_SEPARATOR != '/' ? str_replace(DIRECTORY_SEPARATOR, '/', $path) : $path;
    foreach (glob($path, GLOB_BRACE) as $filename) {
      $files[$filename] = $filename;
      include_once $filename;
    };
  }
  $items = array();
  foreach (get_declared_classes() as $plugin_class) {
    if (is_subclass_of($plugin_class, $interface) && method_exists($plugin_class, 'getInfo')) {
      $reflection = new ReflectionClass($plugin_class);
      $file_path = str_replace(realpath(DRUPAL_ROOT) . DIRECTORY_SEPARATOR, '', realpath($reflection->getFileName()));
      $file_path = DIRECTORY_SEPARATOR != '/' ? str_replace(DIRECTORY_SEPARATOR, '/', $file_path) : $file_path;

      if (array_key_exists($file_path, $files)) {
        $info = call_user_func(array($plugin_class, 'getInfo'));
        $info['class'] = $plugin_class;
        $info['module'] = _fluxservice_discover_module($file_path);
        $items[$info['name']] = $info;
      }
    }
  }
  // Give modules a chance to alter the array of plugin definitions;
  $type_info += array('alter_hook' => $type . '_plugin_info');
  drupal_alter($type_info['alter_hook'], $items);

  return $items;
}

/**
 * Determines the module providing the given file path.
 */
function _fluxservice_discover_module($file_path) {

  $paths = &drupal_static(__FUNCTION__);

  if (!isset($paths)) {
    // Build up a map of modules keyed by their directory.
    foreach (system_list('module_enabled') as $name => $module_info) {
      $paths[dirname($module_info->filename)] = $name;
    }
  }

  $path = str_replace(realpath(DRUPAL_ROOT) . DIRECTORY_SEPARATOR, '', realpath(dirname($file_path)));
  $path = DIRECTORY_SEPARATOR != '/' ? str_replace(DIRECTORY_SEPARATOR, '/', $path) : $path;

  // Go up the path until we match a module up.
  $parts = explode('/', $path);
  while (!isset($paths[$path]) && array_pop($parts)) {
    $path = dirname($path);
  }
  return isset($paths[$path]) ? $paths[$path] : FALSE;
}

/**
 * Implements hook_fluxservice_plugin_type_info().
 */
function fluxservice_fluxservice_plugin_type_info() {
  return array(
    'fluxservice_account' => array(
      'interface' => 'Drupal\fluxservice\Plugin\Entity\AccountInterface',
      'directory' => 'Service',
    ),
    'fluxservice_service' => array(
      'interface' => 'Drupal\fluxservice\Plugin\Entity\ServiceInterface',
      'directory' => 'Service',
    ),
    // Allow exposing entity types via entity classes.
    'fluxservice_entity' => array(
      'interface' => 'Drupal\fluxservice\Entity\EntityInterface',
      'directory' => 'Entity',
    ),
  );
}

/**
 * Retrieves all available service plugin definitions.
 *
 * @param $plugin
 *   (optional) The machine-readable name of a plugin definition.
 *
 * @return array|null
 *   An array of service plugin definitions keyed by their machine-readable name
 *   or a single plugin definition array if $plugin was given and a matching
 *   record could be found. Returns NULL if $plugin was given and there was no
 *   matching plugin definition.
 *
 * @see fluxservice_get_plugin_info()
 */
function fluxservice_get_service_plugin_info($plugin = NULL) {
  return fluxservice_get_plugin_info('fluxservice_service', $plugin);
}

/**
 * Retrieves all available account plugin definitions.
 *
 * @param $plugin
 *   (optional) The machine-readable name of a plugin definition.
 *
 * @return array|null
 *   An array of account plugin definitions keyed by their machine-readable name
 *   or a single plugin definition array if $plugin was given and a matching
 *   record could be found. Returns NULL if $plugin was given and there was no
 *   matching plugin definition.
 *
 * @see fluxservice_get_plugin_info()
 */
function fluxservice_get_account_plugin_info($plugin = NULL) {
  return fluxservice_get_plugin_info('fluxservice_account', $plugin);
}
