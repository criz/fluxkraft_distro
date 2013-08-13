<?php


class xautoload_ClassFinder_NamespaceOrPrefix extends xautoload_ClassFinder_Prefix {

  protected $namespaceMap;

  function __construct() {
    parent::__construct();
    $this->namespaceMap = new xautoload_ClassFinder_Helper_Map();
  }

  /**
   * Register a PSR-0 root folder for a given namespace.
   *
   * @param string $namespace
   *   The namespace, e.g. "My\Namespace", to cover all classes within that,
   *   e.g. My\Namespace\SomeClass, or My\Namespace\Xyz\SomeClass. This does not
   *   cover the root-level class, e.g. My\Namespace
   * @param string $path
   *   The deep path, e.g. "../lib", if classes reside in e.g.
   *   My\Namespace\SomeClass -> ../lib/My/Namespace/SomeClass.php
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerNamespaceRoot($namespace, $path, $lazy_check = TRUE) {
    $namespace_path_fragment = $this->namespacePathFragment($namespace);
    $deep_path = strlen($path) ? $path . DIRECTORY_SEPARATOR : '';
    $deep_path .= $namespace_path_fragment;
    $this->namespaceMap->registerDeepPath($namespace_path_fragment, $deep_path, $lazy_check);
  }

  /**
   * Register PSR-0 root folders for given namespaces.
   *
   * @param array $map
   *   Associative array, the keys are the namespaces, the values are the
   *   directories.
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerNamespacesRoot($map, $lazy_check = TRUE) {
    $deep_map = array();
    foreach ($map as $namespace => $path) {
      $namespace_path_fragment = $this->namespacePathFragment($namespace);
      $deep_path = strlen($path) ? $path . DIRECTORY_SEPARATOR : '';
      $deep_path .= $namespace_path_fragment;
      $deep_map[$namespace_path_fragment][$deep_path] = $lazy_check;
    }
    $this->namespaceMap->registerDeepPaths($deep_map);
  }

  /**
   * Alias for registerNamespaceDeepLocation()
   *
   * @param string $namespace
   *   The namespace, e.g. "My\Namespace"
   * @param string $path
   *   The deep path, e.g. "../lib/My/Namespace"
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerNamespaceDeep($namespace, $path, $lazy_check = TRUE) {
    strlen($namespace);
    $namespace_path_fragment = $this->namespacePathFragment($namespace);
    $deep_path = strlen($path) ? $path . DIRECTORY_SEPARATOR : '';
    $this->namespaceMap->registerDeepPath($namespace_path_fragment, $deep_path, $lazy_check);
  }

  /**
   * Register a number of "deep" namespace directories at once.
   */
  function registerNamespacesDeep($map, $lazy_check = TRUE) {
    $deep_map = array();
    foreach ($map as $namespace => $path) {
      $namespace_path_fragment = $this->namespacePathFragment($namespace);
      $deep_path = strlen($path) ? $path . DIRECTORY_SEPARATOR : '';
      $deep_map[$namespace_path_fragment][$deep_path] = $lazy_check;
    }
    $this->namespaceMap->registerDeepPaths($deep_map);
  }

  /**
   * Register a deep filesystem location for a given namespace.
   *
   * @param string $namespace
   *   The namespace, e.g. "My\Namespace"
   * @param string $path
   *   The deep path, e.g. "../lib/My/Namespace"
   * @param boolean $lazy_check
   *   If TRUE, then we are not sure if the directory at $path actually exists.
   *   If during the process we find the directory to be nonexistent, we
   *   unregister the path.
   */
  function registerNamespaceDeepLocation($namespace, $path, $lazy_check = TRUE) {
    $namespace_path_fragment = $this->namespacePathFragment($namespace);
    $deep_path = strlen($path) ? $path . DIRECTORY_SEPARATOR : '';
    $this->namespaceMap->registerDeepPath($namespace_path_fragment, $deep_path, $lazy_check);
  }

  /**
   * Legacy: Plugins were called Handlers before.
   */
  function registerNamespaceHandler($prefix, $plugin) {
    return $this->registerNamespacePlugin($prefix, $plugin);
  }

  /**
   * Register a plugin for a namespace.
   *
   * @param string $namespace
   *   The namespace, e.g. "My\Library"
   * @param xautoload_FinderPlugin_Interface $plugin
   *   The plugin.
   */
  function registerNamespacePlugin($namespace, $plugin) {
    $namespace_path_fragment = $this->namespacePathFragment($namespace);
    $this->namespaceMap->registerPlugin($namespace_path_fragment, $plugin);
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

    if ('\\' === $class[0]) {
      $class = substr($class, 1);
    }

    // First check if the literal class name is registered.
    if (!empty($this->classes[$class])) {
      foreach ($this->classes[$class] as $filepath => $true) {
        if ($api->suggestFile($filepath)) {
          return TRUE;
        }
      }
    }

    if (FALSE !== $pos = strrpos($class, '\\')) {

      // Loop through positions of '\\', backwards.
      $namespace_path_fragment = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 0, $pos + 1));
      $path_suffix = str_replace('_', DIRECTORY_SEPARATOR, substr($class, $pos + 1)) . '.php';
      if ($this->namespaceMap->findFile_map($api, $namespace_path_fragment, $path_suffix)) {
        return TRUE;
      }
    }
    else {

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
  }

  /**
   * Replace the namespace separator with directory separator.
   *
   * @param string $namespace
   *   Namespace without trailing namespace separator.
   *
   * @return string
   *   Path fragment representing the namespace, with trailing DIRECTORY_SEPARATOR.
   */
  protected function namespacePathFragment($namespace) {
    return
      strlen($namespace)
      ? str_replace('\\', DIRECTORY_SEPARATOR, $namespace . '\\')
      : ''
    ;
  }
}
