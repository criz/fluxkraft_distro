<?php

interface xautoload_BootSchedule_Interface {

  /**
   * Init the phase where the database is available,
   * and register the namespaces and prefixes for all modules.
   */
  function initBootstrapPhase();

  /**
   * Init the phase where all *.module files are loaded,
   * and run hook_xautoload() on all modules that implement it.
   */
  function initMainPhase();

  /**
   * Add modules after they have been enabled or installed.
   *
   * @param array $modules
   *   Array of module names, with numeric keys.
   */
  function modulesInstalledOrEnabled(array $modules);
}
