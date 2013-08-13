<?php

class xautoload_BootSchedule_Default implements xautoload_BootSchedule_Interface {

  /**
   * @var xautoload_BootSchedule_Helper_Interface
   *   A helper object that knows how to register namespaces or prefixes for
   *   Drupal extensions (modules/themes).
   */
  protected $helper;

  /**
   * Constructs an xautoload_BootSchedule_Default.
   *
   * @param xautoload_BootSchedule_Helper_Interface $helper
   *   A helper object that knows how to register namespaces or prefixes for
   *   Drupal extensions (modules/themes).
   */
  function __construct($helper) {
    $this->helper = $helper;
  }

  /**
   * Init the phase where the database is available,
   * and register the namespaces and prefixes for all modules.
   */
  function initBootstrapPhase() {
    // Doing this directly tends to be a lot faster than system_list().
    $extensions = db_query("SELECT name, filename, type from {system} WHERE status = 1")->fetchAll();
    $this->helper->registerExtensions($extensions);
  }

  /**
   * Init the phase where all *.module files are loaded,
   * and run hook_xautoload() on all modules that implement it.
   */
  function initMainPhase() {
    $this->helper->invokeRegistrationHook('xautoload');
  }

  /**
   * Add modules after they have been enabled or installed.
   *
   * @param array $modules
   *   Array of module names, with numeric keys.
   */
  function modulesInstalledOrEnabled(array $modules) {

    // Load information about the newly enabled modules.
    $q = db_select('system');
    $q->condition('name', $modules);
    $q->fields('system', array('name', 'filename', 'type'));
    $extensions = $q->execute()->fetchAll();

    // Register the namespaces / prefixes for those modules.
    $this->helper->registerExtensions($extensions);
  }

  /**
   * Verify that the boot schedule helper uses the correct finder instance.
   *
   * @param xautoload_ClassFinder_Interface $finder
   *   The class finder.
   */
  function verifyFinderInstance($finder) {
    $this->helper->verifyFinderInstance($finder);
  }
}
