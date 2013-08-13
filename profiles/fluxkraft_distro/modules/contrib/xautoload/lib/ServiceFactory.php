<?php


class xautoload_ServiceFactory {

  function main($services) {
    return new xautoload_Main($services);
  }

  function registrationHelper($services) {
    // Build the helper object.
    if (version_compare(PHP_VERSION, '5.3') >= 0) {
      $helper = new xautoload_BootSchedule_Helper_PHP53($services->classFinder);
    }
    else {
      $helper = new xautoload_BootSchedule_Helper_PHP52($services->classFinder);
    }
    return $helper;
  }

  /**
   * Drupal bootstrap registration schedule.
   *
   * @return xautoload_BootSchedule_Interface
   *   Object that will register Drupal-related namespaces and prefixes at
   *   applicable moments during the request.
   */
  function schedule($services) {

    // Build the registration schedule.
    $schedule = new xautoload_BootSchedule_Default($services->registrationHelper);

    // To avoid duplicate registration, Drupal hooks always operate on the proxy
    // schedule, never the real one.
    return new xautoload_BootSchedule_Proxy($schedule);
  }

  /**
   * Loader manager
   *
   * @return object
   *   Object that can
   *   - create class loaders with different cache mechanics,
   *   - register the one for the currently configured cache method, and also
   *   - switch between cache methods.
   */
  function loaderManager($services) {

    // Build the loader manager.
    $proxyFinder = $services->proxyFinder;

    $loaderFactory = new xautoload_LoaderFactory($proxyFinder);
    $services->apcKeyManager->observeApcPrefix($loaderFactory);

    $loaderManager = new xautoload_LoaderManager($loaderFactory);
    $services->apcKeyManager->observeApcPrefix($loaderManager);

    return $loaderManager;
  }

  function apcKeyManager($services) {

    // Check if the system supports APC cache method.
    if (1
      && extension_loaded('apc')
      && function_exists('apc_store')
      && function_exists('apc_fetch')
    ) {
      return new xautoload_ApcKeyManager_Enabled('drupal.xautoload.' . $GLOBALS['drupal_hash_salt'] . '.apc_prefix');
    }
    else {
      return new xautoload_ApcKeyManager_Disabled();
    }
  }

  /**
   * Proxy class finder.
   *
   * @return xautoload_ClassFinder_Interface
   *   Proxy object wrapping the class finder.
   *   This is used to delay namespace registration until the first time the
   *   finder is actually used.
   *   (which might never happen thanks to the APC cache)
   */
  function proxyFinder($services) {
    // The class finder is cheap to create,
    // so it can use an identity proxy.
    $proxy = new xautoload_ClassFinder_Proxy($services->finder);
    $proxy->proxyObserveInstantiation(array($services->schedule, 'setFinder'));
    return $proxy;
  }

  /**
   * The class finder (alias for 'finder').
   *
   * @return xautoload_ClassFinder_Interface
   *   Object that can find classes,
   *   and provides methods to register namespaces and prefixes.
   *   Note: The findClass() method expects an InjectedAPI argument.
   */
  function classFinder($services) {
    return $services->finder;
  }

  /**
   * The class finder (alias for 'classFinder').
   *
   * @return xautoload_ClassFinder_Interface
   *   Object that can find classes,
   *   and provides methods to register namespaces and prefixes.
   *   Notes:
   *   - The findClass() method expects an InjectedAPI argument.
   *   - namespaces are only supported since PHP 5.3
   */
  function finder($services) {

    if (version_compare(PHP_VERSION, '5.3') >= 0) {
      // Create a finder with namespace support.
      return new xautoload_ClassFinder_NamespaceOrPrefix();
    }
    else {
      // Create a finder without namespaces support.
      return new xautoload_ClassFinder_Prefix();
    }
  }
}

