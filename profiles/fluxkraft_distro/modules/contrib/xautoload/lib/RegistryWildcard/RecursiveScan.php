<?php
/**
 * This file is autoloaded with the regular uncached xautoload.
 */


/**
 * Scan directories for wildcard files[] instructions in a module's info file.
 */
class xautoload_RegistryWildcard_RecursiveScan {

  protected $_array;
  protected $_value;
  protected $_plus = array();
  protected $_minus = array();

  function __construct(&$result) {
    $this->result =& $result;
  }

  function dpm() {
    dpm($this->minus);
    dpm($this->plus);
  }

  function check($path, $value) {
    $this->value = $value;
    if ($this->_check($path)) {
      unset($this->result[$path]);
      $this->minus[$path] = TRUE;
    }
  }

  protected function _abc($a, $b, $c = NULL) {
    if (is_dir($a)) {
      foreach (scandir($a) as $candidate) {
        if ($this->_validCand($candidate, $b)) {
          if (!isset($c)) {
            if ($b === '**') {
              $this->_abc("$a/$candidate", '**');
            }
            $this->_file("$a/$candidate");
          }
          else{
            if (!$this->_check("$a/$candidate/$c")) {
              $this->_file("$a/$candidate/$c");
            }
            if ($b === '**') {
              $this->_abc("$a/$candidate", '**', $c);
            }
          }
        }
      }
    }
  }

  protected function _validCand($candidate, $b) {

    if ($candidate == '.' || $candidate == '..') {
      return FALSE;
    }
    if (strpos($candidate, '*') !== FALSE) {
      return FALSE;
    }
    if ($b == '*' || $b == '**') {
      return TRUE;
    }

    // More complex wildcard string.
    $fragments = array();
    foreach (explode('*', $b) as $fragment) {
      $fragments[] = preg_quote($fragment);
    }
    $regex = implode('.*', $fragments);
    return preg_match("/^$regex$/", $candidate);
  }

  protected function _check($path) {
    if (preg_match('#^([^\*]*)/(.*\*.*)$#', $path, $m)) {
      list(, $a, $b) = $m;
      list($b, $c) = $result = explode('/', $b, 2) + array(NULL, NULL);
      if ($b === '**' && isset($c)) {
        $this->_check("$a/$c");
      }
      $this->_abc($a, $b, $c);
      return TRUE;
    }
    else {
      // Not a wildcard string
      return FALSE;
    }
  }

  protected function _file($path) {
    if (is_file($path)) {
      $this->result[$path] = $this->value;
      $this->plus[$path] = TRUE;
    }
  }
}
