<?php

class xautoload_Container_Identity {

  protected $arg;

  function __construct($arg) {
    $this->arg = $arg;
  }

  function get() {
    return $this->arg;
  }
}
