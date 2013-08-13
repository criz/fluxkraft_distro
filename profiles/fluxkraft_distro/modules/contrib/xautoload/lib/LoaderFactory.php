<?php

/**
 * This thing has an overview of available class loaders with different cache
 * mechanics. It can detect the currently applicable cache method, and it can
 * switch between cache methods.
 *
 * It should be mentioned that "loader" and "finder" mean two separate things
 * in xautoload. The "finder" knows all the namespaces and directories. The
 * "loader" is for the cache layer and for file inclusion, and it is plugged
 * with a "finder" to actually find the class on a cache miss.
 */
class xautoload_LoaderFactory {

  protected $proxyFinder;
  protected $apcPrefix;

  /**
   * @param xautoload_ClassFinder_Proxy $proxyFinder
   *   The class finder to plug into our loaders.
   */
  function __construct($proxyFinder) {
    $this->proxyFinder = $proxyFinder;
  }

  /**
   * This is called on instantiation,
   * and whenever the APC prefix is renewed,
   * but only if the system actually supports APC.
   */
  function setApcPrefix($apc_prefix) {
    $this->apcPrefix = $apc_prefix;
  }

  /**
   * Build a loader for a given mode.
   *
   * @param string $mode
   *   Loader mode, e.g. 'apc' or 'default'.
   *
   * @return xautoload_ClassLoader_Interface
   *   The class loader.
   */
  function buildLoader($mode) {

    switch ($mode) {

      case 'apc_lazy':
        if (isset($this->apcPrefix)) {

          // Create a loader that uses the proxy finder.
          $loader = new xautoload_ClassLoader_ApcCache($this->proxyFinder, $this->apcPrefix);

          // Give the loader the real finder, once the proxy fires.
          $this->proxyFinder->proxyObserveInstantiation(array($loader, 'setFinder'));

          // Return the loader.
          return $loader;
        }
        break;

      case 'apc':
        if (isset($this->apcPrefix)) {
          $finder = $this->proxyFinder->proxyGetInstance();
          return new xautoload_ClassLoader_ApcCache($finder, $this->apcPrefix);
        }
        break;

      case 'default':
      case 'dev':
      default:
        $finder = $this->proxyFinder->proxyGetInstance();
        return new xautoload_ClassLoader_NoCache($finder);
    }

    // Loader could not be created, because the respective cache mechanic is not available.
    return FALSE;
  }
}
