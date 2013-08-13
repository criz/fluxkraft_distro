<?php


class xautoload_ClassFinder_Prefix implements xautoload_ClassFinder_Interface {

  protected $prefixMap;
  protected $classes = array();

  function __construct() {
    $this->prefixMap = new xautoload_ClassFinder_Helper_Map();
  }

  /**
   * Register a PEAR-style root path for a given class prefix.
   *
   * @param string $prefix
   *   Prefix, e.g. "My_Prefix", for classes like "My_Prefix_SomeClass".
   *   This does ALSO cover the class named "My_Prefix" itself.
   * @param string $root_path
   *   Root path, e.g. "../lib" or "../src", so that classes can be placed e.g.
   *   My_Prefix_SomeClass -> ../lib/My/Prefix/SomeClass.php
   *   My_Prefix -> ../lib/My/Prefix.php
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerPrefixRoot($prefix, $root_path, $lazy_check = TRUE) {
    $prefix_path_fragment = $this->prefixPathFragment($prefix);
    $deep_path = strlen($root_path) ? $root_path . DIRECTORY_SEPARATOR : $root_path;
    $deep_path .= $prefix_path_fragment;
    $this->prefixMap->registerDeepPath($prefix_path_fragment, $deep_path, $lazy_check);

    if (strlen($prefix)) {
      // We assume that the class named $prefix is also found at this path.
      $filepath = substr($deep_path, 0, -1) . '.php';
      $this->registerClass($prefix, $filepath);
    }
  }

  /**
   * Register an array of PEAR-style deep paths for given class prefixes.
   *
   * Note:
   *   This actually goes beyond PEAR style, because it also allows "shallow"
   *   PEAR-like structures like
   *     my_library_Some_Class -> (library dir)/src/Some/Class.php
   *   instead of
   *     my_library_Some_Class -> (library dir)/src/my/library/Some/Class.php
   *   via
   *     $finder->registerPrefixDeep('my_library', "$library_dir/src");
   *
   * @param array $map
   *   Associative array, the keys are the prefixes, the values are the
   *   directories.
   *   This does NOT cover the class named $prefix itself.
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerPrefixesRoot($map, $lazy_check = TRUE) {
    $deep_map = array();
    $class_map = array();
    foreach ($map as $prefix => $path) {
      $prefix_path_fragment = $this->prefixPathFragment($prefix);
      $deep_path = strlen($path) ? $path . DIRECTORY_SEPARATOR : '';
      $deep_path .= $prefix_path_fragment;
      $deep_map[$prefix_path_fragment][$deep_path] = $lazy_check;

      // Register the class with name $prefix.
      if (strlen($prefix)) {
        $filepath = substr($deep_path, 0, -1) . '.php';
        $this->classes[$prefix][$filepath] = TRUE;
      }
    }
    $this->prefixMap->registerDeepPaths($deep_map);
  }

  /**
   * Register a PEAR-style deep path for a given class prefix.
   *
   * Note:
   *   This actually goes beyond PEAR style, because it also allows things like
   *     my_library_Some_Class -> (library dir)/src/Some/Class.php
   *   instead of
   *     my_library_Some_Class -> (library dir)/src/my/library/Some/Class.php
   *   via
   *     $finder->registerPrefixDeep('my_library', "$library_dir/src");
   *
   * @param string $prefix
   *   Prefix, e.g. "My_Prefix", for classes like "My_Prefix_SomeClass".
   *   This does NOT cover the class named "My_Prefix" itself.
   * @param string $deep_path
   *   The deep path, e.g. "../lib/My/Prefix", for classes placed in
   *   My_Prefix_SomeClass -> ../lib/My/Prefix/SomeClass.php
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerPrefixDeep($prefix, $deep_path, $lazy_check = TRUE) {
    $this->registerPrefixDeepLocation($prefix, $deep_path, $lazy_check);
  }

  /**
   * Register an array of PEAR-style deep paths for given class prefixes.
   *
   * Note:
   *   This actually goes beyond PEAR style, because it also allows "shallow"
   *   PEAR-like structures like
   *     my_library_Some_Class -> (library dir)/src/Some/Class.php
   *   instead of
   *     my_library_Some_Class -> (library dir)/src/my/library/Some/Class.php
   *   via
   *     $finder->registerPrefixDeep('my_library', "$library_dir/src");
   *
   * @param array $map
   *   Associative array, the keys are the prefixes, the values are the
   *   directories.
   *   This does NOT cover the class named $prefix itself.
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerPrefixesDeep($map, $lazy_check = TRUE) {
    $deep_map = array();
    foreach ($map as $prefix => $path) {
      $prefix_path_fragment = $this->prefixPathFragment($prefix);
      $deep_path = strlen($path) ? $path . DIRECTORY_SEPARATOR : '';
      $deep_map[$prefix_path_fragment][$deep_path] = $lazy_check;
    }
    $this->prefixMap->registerDeepPaths($deep_map);
  }

  /**
   * Register a filepath for an individual class.
   *
   * @param string $class
   *   The class, e.g. My_Class
   * @param string $file_path
   *   The path, e.g. "../lib/My/Class.php".
   */
  function registerClass($class, $file_path) {
    $this->classes[$class][$file_path] = TRUE;
  }

  /**
   * Register an array ("map") of classes to file paths.
   *
   * @param array $classes
   *   The map of classes to file paths.
   */
  function registerClasses($classes) {
    foreach ($classes as $class => $file_path) {
      $this->classes[$class][$file_path] = TRUE;
    }
  }

  /**
   * Register a filesystem location for a given class prefix.
   *
   * @param string $prefix
   *   The prefix, e.g. "My_Prefix"
   * @param string $path
   *   The deep filesystem location, e.g. "../lib/My/Prefix".
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerPrefixDeepLocation($prefix, $path, $lazy_check = FALSE) {
    $prefix_path_fragment = $this->prefixPathFragment($prefix);
    $this->prefixMap->registerDeepPath($prefix_path_fragment, $path . '/', $lazy_check);
  }

  /**
   * Legacy: Plugins were called Handlers before.
   */
  function registerPrefixHandler($prefix, $plugin) {
    return $this->registerPrefixPlugin($prefix, $plugin);
  }

  /**
   * Register a plugin for a prefix.
   *
   * @param string $prefix
   *   The prefix, e.g. "My_Library"
   * @param xautoload_FinderPlugin_Interface $plugin
   *   The plugin. See 
   */
  function registerPrefixPlugin($prefix, $plugin) {
    $prefix_path_fragment = $this->prefixPathFragment($prefix);
    $this->prefixMap->registerPlugin($prefix_path_fragment, $plugin);
  }

  /**
   * Finds the path to the file where the class is defined.
   *
   * @param xautoload_InjectedAPI_findFile $api
   *   API object with a suggestFile() method.
   *   We are supposed to call $api->suggestFile($file) with all suggestions we
   *   can find, until it returns TRUE. Once suggestFile() returns TRUE, we stop
   *   and return TRUE as well. The $file will be in the $api object, so we
   *   don't need to return it.
   * @param string $class
   *   The name of the class, with all namespaces prepended.
   *   E.g. Some\Namespace\Some\Class
   *
   * @return TRUE|NULL
   *   TRUE, if we found the file for the class.
   *   That is, if the $api->suggestFile($file) method returned TRUE one time.
   *   NULL, if we have no more suggestions.
   */
  function findFile($api, $class) {

    // First check if the literal class name is registered.
    if (!empty($this->classes[$class])) {
      foreach ($this->classes[$class] as $filepath => $true) {
        if ($api->suggestFile($filepath)) {
          return TRUE;
        }
      }
    }

    // The class is not within a namespace.
    // Fall back to the prefix-based finder.
    $prefix_path_fragment = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    if ('_' === $class{0}) {
      $prefix_path_fragment{0} = '_';
    }
    if ($this->prefixMap->findFile_map($api, $prefix_path_fragment, '')) {
      return TRUE;
    }
  }

  /**
   * Convert the underscores of a prefix into directory separators.
   *
   * @param string $prefix
   *   Prefix, without trailing underscore.
   *
   * @return string
   *   Path fragment representing the prefix, with trailing DIRECTORY_SEPARATOR.
   */
  protected function prefixPathFragment($prefix) {
    return
      strlen($prefix)
      ? str_replace('_', DIRECTORY_SEPARATOR, $prefix . '_')
      : ''
    ;
  }
}
