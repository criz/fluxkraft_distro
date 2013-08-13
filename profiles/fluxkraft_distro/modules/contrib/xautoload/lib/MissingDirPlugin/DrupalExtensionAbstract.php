<?php

abstract class xautoload_MissingDirPlugin_DrupalExtensionAbstract {

  protected $type;
  protected $shallow;

  /**
   * @param string $type
   *   The extension type, e.g. "module" or "theme".
   * @param boolean $shallow
   *   Whether to use a "shallow" variation of PSR0 or PEAR.
   */
  function __construct($type, $shallow = FALSE) {
    $this->type = $type;
    $this->shallow = $shallow;
  }

  abstract function alternativeDir($path_fragment);
}
