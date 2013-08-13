<?php

class xautoload_BootSchedule_Proxy extends xautoload_Container_IdentityProxyObject implements xautoload_BootSchedule_Interface {

  /**
   * Init the phase where the database is available, and schedule the
   * registration of the namespaces and prefixes for all modules.
   */
  function initBootstrapPhase() {
    $this->proxyScheduleOperation(__FUNCTION__);
  }

  /**
   * Init the phase where all *.module files are loaded, and schedule the
   * running of hook_xautoload() on all modules that implement it.
   */
  function initMainPhase() {
    $this->proxyScheduleOperation(__FUNCTION__);
  }

  /**
   * Add modules after they have been enabled or installed.
   *
   * @param array $modules
   *   Array of module names, with numeric keys.
   */
  function modulesInstalledOrEnabled(array $modules) {
    $this->proxyGetInstance()->modulesInstalledOrEnabled($modules);
  }

  /**
   * The proxy finder has materialized,
   * and needs all scheduled namespace registrations to run now.
   *
   * @param xautoload_ClassFinder_Interface $finder
   *   The class finder where we register the namespaces and prefixes.
   */
  function setFinder($finder) {

    // Activate instantiation of the proxied schedule.
    // This will trigger all scheduled operations to run.
    $schedule = $this->proxyGetInstance();

    // The schedule already knows the finder object.
    // Verify that it has the correct finder.
    $schedule->verifyFinderInstance($finder);
  }
}
