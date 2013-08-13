<?php

class xautoload_ApcKeyManager_Enabled implements xautoload_ApcKeyManager_Interface {

  protected $apcKey;
  protected $apcPrefix;
  protected $observers = array();

  function __construct($apc_key) {

    $this->apcKey = $apc_key;
    $this->apcPrefix = apc_fetch($this->apcKey);

    if (empty($this->apcPrefix)) {
      $this->renewApcPrefix();
    }
  }

  function observeApcPrefix($observer) {
    $observer->setApcPrefix($this->apcPrefix);
    $this->observers[] = $observer;
  }

  function renewApcPrefix() {

    // Generate a new APC prefix
    $this->apcPrefix = xautoload_Util::randomString();

    // Store the APC prefix
    apc_store($this->apcKey, $this->apcPrefix);

    foreach ($this->observers as $observer) {
      $observer->setApcPrefix($this->apcPrefix);
    }
  }
}
