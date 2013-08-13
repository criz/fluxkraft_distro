<?php


class xautoload_ClassLoader_ApcAggressive extends xautoload_ClassLoader_ApcCache {

  /**
   * Find the file where we expect a class to be defined.
   *
   * @param string $class
   *   The class to find.
   *
   * @return string
   *   File where the class is assumed to be defined.
   */
  function findFile($class) {

    if (FALSE === $file = apc_fetch($this->prefix . $class)) {
      apc_store($this->prefix . $class, $file = parent::findFile($class));
    }

    return $file;
  }
}
