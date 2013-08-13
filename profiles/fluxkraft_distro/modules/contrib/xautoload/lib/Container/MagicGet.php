<?php

class xautoload_Container_MagicGet {

  protected $container;
  protected $arg;

  function __construct($container, $key) {
    $this->container = $container;
    $this->key = $key;
  }

  function get() {
    return $this->container->__get($this->key);
  }
}
