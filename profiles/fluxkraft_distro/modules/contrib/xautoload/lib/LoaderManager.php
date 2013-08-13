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
class xautoload_LoaderManager {

  protected $loaderFactory;
  protected $mode;
  protected $loaders = array();

  /**
   * @param xautoload_LoaderFactory $loaderFactory
   *   Object that can create different class loaders.
   */
  function __construct($loaderFactory) {
    $this->loaderFactory = $loaderFactory;
  }

  /**
   * Register the loader for the given mode, and unregister other loaders.
   * This can be used both for initial registration, and later on to change the
   * cache mode.
   *
   * @param string|NULL $mode
   *   Loader mode, e.g. 'apc' or 'default'.
   *   If NULL, the loader mode will be detected from settings.
   * @param boolean $prepend
   *   If TRUE, the loader will be prepended before other loaders.
   *   If FALSE, the loader will be inserted into the dedicated position between
   *     other loaders.
   */
  function register($mode = NULL, $prepend = FALSE) {
    if (!isset($mode)) {
      $mode = $this->detectLoaderMode();
    }
    $success = $this->initLoaderMode($mode);
    if (!$success) {
      // Fallback to 'default' mode.
      $this->initLoaderMode('default');
      $mode = 'default';
    }
    $this->switchLoaderMode($mode, $prepend);
  }

  /**
   * This is called on instantiation,
   * and whenever the APC prefix is renewed,
   * but only if the system actually supports APC.
   */
  function setApcPrefix($apc_prefix) {

    // Set it in all apc-based loaders.
    foreach ($this->loaders as $loader_key => $loader) {
      if (1
        && 'apc_' === substr($loader_key . '_', 0, 4)
        && method_exists($loader, 'setApcPrefix')
      ) {
        $loader->setApcPrefix($apc_prefix);
      }
    }
  }

  /**
   * Detect the loader mode.
   *
   * @return string
   *   Loader mode, e.g. 'apc' or 'default'.
   */
  protected function detectLoaderMode() {
    if (function_exists('variable_get')) {
      $mode = variable_get('xautoload_cache_mode', 'default');
      return $mode;
    }
    return 'default';
  }

  /**
   * Change the loader mode.
   *
   * @param string $mode
   *   Loader mode, e.g. 'apc' or 'default'.
   * @param boolean $prepend
   *   If TRUE, the loader will be prepended before other loaders.
   *   If FALSE, the loader will be inserted into the dedicated position between
   *     other loaders.
   */
  protected function switchLoaderMode($mode, $prepend) {
    if ($mode === $this->mode && !$prepend) {
      return;
    }
    if (isset($this->loaders[$this->mode])) {
      // Unregister the original loader.
      $this->loaders[$this->mode]->unregister();
    }
    $this->registerLoader($this->loaders[$mode], $prepend);
    $this->mode = $mode;
  }

  /**
   * Create the loader for a given mode, if it does not exist yet.
   *
   * @param string $mode
   *   Loader mode, e.g. 'apc' or 'default'.
   *
   * @return boolean
   *   TRUE, if the loader for the mode does now exist.
   */
  protected function initLoaderMode($mode) {
    if (!isset($this->loaders[$mode])) {
      $this->loaders[$mode] = $this->loaderFactory->buildLoader($mode);
    }
    return !empty($this->loaders[$mode]);
  }

  /**
   * Register the new loader in the correct position in the spl autoload stack.
   *
   * @param object $loader
   *   The loader to register.
   */
  protected function registerLoader($loader, $prepend) {
    // TODO: Figure out correct position in spl autoload stack.
    $loader->register($prepend);
  }
}
