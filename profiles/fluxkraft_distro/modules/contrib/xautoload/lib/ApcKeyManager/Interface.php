<?php

interface xautoload_ApcKeyManager_Interface {

  function observeApcPrefix($observer);

  function renewApcPrefix();
}
