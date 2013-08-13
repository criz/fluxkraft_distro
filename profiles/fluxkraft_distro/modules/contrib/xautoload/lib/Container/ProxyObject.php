<?php

abstract class xautoload_Container_ProxyObject {

  protected $observers = array();
  protected $scheduled = array();
  protected $instance;

  function proxyObserveInstantiation($callback) {
    if (!isset($this->instance)) {
      $this->observers[] = $callback;
    }
    else {
      call_user_func_array($callback, $this->instance);
    }
  }

  function proxyGetInstance() {
    if (!isset($this->instance)) {
      $this->instance = $this->proxyCreateInstance();
      foreach ($this->observers as $callback) {
        call_user_func($callback, $this->instance);
      }
      foreach ($this->scheduled as $info) {
        list($method, $args) = $info;
        call_user_func_array(array($this->instance, $method), $args);
      }
    }
    return $this->instance;
  }

  function proxyScheduleOperation($method, $args = array()) {
    if (!isset($this->instance)) {
      $this->scheduled[] = array($method, $args);
    }
    else {
      call_user_func_array(array($this->instance, $method), $args);
    }
  }

  abstract protected function proxyCreateInstance();
}
