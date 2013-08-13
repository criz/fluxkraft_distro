<?php

abstract class xautoload_BootSchedule_Helper_Base implements xautoload_BootSchedule_Helper_Interface {

  protected $finder;

  /**
   * @param xautoload_ClassFinder_Interface $finder
   *   The class finder where we register the namespaces and prefixes.
   */
  function __construct($finder) {
    $this->finder = $finder;
  }

  /**
   * @param xautoload_ClassFinder_Interface $finder
   *   The class finder where we register the namespaces and prefixes.
   */
  function verifyFinderInstance($finder) {
    if ($finder !== $this->finder) {
      throw new Exception("Wrong finder instance.");
    }
  }

  /**
   * Invoke hook_xautoload or another registration hook.
   */
  function invokeRegistrationHook($hook) {
    // Let other modules register stuff to the finder via hook_xautoload().
    $api = new xautoload_InjectedAPI_hookXautoload($this->finder);
    foreach (module_implements($hook) as $module) {
      $api->setModule($module);
      $f = $module . '_' . $hook;
      $f($api);
    }
  }

  /**
   * Register prefix maps, one map per extension type.
   *
   * @param array $prefix_maps
   *   Prefix maps for different extension types. Modules and themes are
   *   registered speparately, because they need a different MissingDirPlugin.
   */
  protected function registerPrefixMaps($prefix_maps) {
    foreach ($prefix_maps as $type => $map) {
      $missing_dir_plugin = new xautoload_MissingDirPlugin_DrupalExtensionPrefix($type, TRUE);
      $this->finder->registerPrefixesDeep($map, $missing_dir_plugin);
    }
  }

  /**
   * Register namespace maps, one map per extension type.
   *
   * @param array $namespace_maps
   *   Namespace maps for different extension types. Modules and themes are
   *   registered speparately, because they need a different MissingDirPlugin.
   */
  protected function registerNamespaceMaps($namespace_maps) {
    foreach ($namespace_maps as $type => $map) {
      $missing_dir_plugin = new xautoload_MissingDirPlugin_DrupalExtensionNamespace($type, FALSE);
      $this->finder->registerNamespacesDeep($map, $missing_dir_plugin);
    }
  }
}
