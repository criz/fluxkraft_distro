<?php

class xautoload_Container_IdentityProxyObject extends xautoload_Container_ProxyObject {

  protected $identityInstance;

  function __construct($instance) {
    $this->identityInstance = $instance;
  }

  protected function proxyCreateInstance() {
    return $this->identityInstance;
  }
}
