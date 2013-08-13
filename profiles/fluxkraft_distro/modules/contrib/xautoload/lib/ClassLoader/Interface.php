<?php


/**
 * Behaves mostly like the Symfony ClassLoader classes.
 */
interface xautoload_ClassLoader_Interface {

  /**
   * Registers this instance as an autoloader.
   *
   * @param boolean $prepend
   *   If TRUE, the loader will be prepended. Otherwise, it will be appended.
   */
  function register($prepend = FALSE);

  /**
   * Unregister this instance as an autoloader.
   */
  function unregister();

  /**
   * Callback for class loading. This will include ("require") the file found.
   *
   * @param string $class
   *   The class to load.
   */
  function loadClass($class);

  /**
   * For compatibility, it is possible to use the class loader as a finder.
   *
   * @param string $class
   *   The class to find.
   *
   * @return string
   *   File where the class is assumed to be.
   */
  function findFile($class);
}
