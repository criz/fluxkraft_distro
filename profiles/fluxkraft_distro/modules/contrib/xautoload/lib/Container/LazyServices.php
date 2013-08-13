<?php


class xautoload_Container_LazyServices {

  protected $factory;
  protected $services = array();

  function get($key) {
    if (!isset($this->services[$key])) {
      $this->services[$key] = $this->factory->$key($this);
      if (!isset($this->services[$key])) {
        $this->services[$key] = FALSE;
      }
    }
    return $this->services[$key];
  }

  function reset($key) {
    $this->services[$key] = NULL;
  }

  /**
   * Register a new service under the given key.
   */
  function set($key, $service) {
    $this->services[$key] = $service;
  }

  function __get($key) {
    return $this->get($key);
  }

  function __construct($factory) {
    $this->factory = $factory;
  }
}
