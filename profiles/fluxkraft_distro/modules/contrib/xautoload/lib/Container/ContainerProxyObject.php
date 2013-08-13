<?php

class xautoload_Container_ContainerProxyObject extends xautoload_Container_ProxyObject {

  protected $container;
  protected $key;

  function __construct($container, $key) {
    $this->container = $container;
    $this->key = $key;
  }

  protected function proxyCreateInstance() {
    return $this->container->get($this->key);
  }
}
