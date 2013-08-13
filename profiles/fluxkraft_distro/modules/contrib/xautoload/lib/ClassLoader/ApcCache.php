<?php


class xautoload_ClassLoader_ApcCache extends xautoload_ClassLoader_NoCache {

  protected $prefix;

  /**
   * @param object $finder
   *   Another ClassFinder to delegate to, if the class is not in the cache.
   * @param string $prefix
   *   A prefix for the storage key in APC.
   */
  function __construct($finder, $prefix) {
    if (!extension_loaded('apc') || !function_exists('apc_store')) {
      throw new Exception('Unable to use xautoload_ClassLoader_ApcCache, because APC is not enabled.');
    }
    $this->prefix = $prefix;
    parent::__construct($finder);
  }

  /**
   * Set the APC prefix after a flush cache.
   *
   * @param string $prefix
   *   A prefix for the storage key in APC.
   */
  function setApcPrefix($prefix) {
    $this->prefix = $prefix;
  }

  /**
   * Callback for class loading. This will include ("require") the file found.
   *
   * @param string $class
   *   The class to load.
   */
  function loadClass($class) {

    if ($file = $this->findFile($class)) {
      require $file;
    }
  }

  /**
   * For compatibility, it is possible to use the class loader as a finder.
   *
   * @param string $class
   *   The class to find.
   *
   * @return string
   *   File where the class is assumed to be.
   */
  function findFile($class) {

    if (
      (FALSE === $file = apc_fetch($this->prefix . $class)) ||
      (!empty($file) && !is_file($file))
    ) {
      // Resolve cache miss.
      apc_store($this->prefix . $class, $file = parent::findFile($class));
    }

    return $file;
  }
}
