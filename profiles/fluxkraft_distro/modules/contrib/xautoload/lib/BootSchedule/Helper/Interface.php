<?php

interface xautoload_BootSchedule_Helper_Interface {

  /**
   * Invoke hook_xautoload or another registration hook
   * on all modules that implement it.
   *
   * @param string $hook
   *   E.g. 'xautoload' for hook_xautoload().
   */
  function invokeRegistrationHook($hook);

  /**
   * Register prefixes for enabled Drupal extensions (modules/themes).
   *
   * @param array $extensions
   *   Info about extensions.
   */
  function registerExtensions($extensions);
}
