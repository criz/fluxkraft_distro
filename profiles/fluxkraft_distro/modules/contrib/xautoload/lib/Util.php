<?php

/**
 * A number of static methods that don't interact with any global state.
 */
class xautoload_Util {

  /**
   * Generate a random string made of uppercase and lowercase characters and numbers.
   *
   * @param int $length
   *   Length of the random string to generate
   *
   * @return string
   *   Random string of the specified length
   */
  static function randomString($length = 30) {

    // $chars - allowed characters
    $chars = 'abcdefghijklmnopqrstuvwxyz' .
             'ABCDEFGHIJKLMNOPQRSTUVWXYZ' .
             '1234567890';

    srand((double)microtime() * 1000000);

    $pass = '';
    for ($i = 0; $i < $length; ++$i) {
      $num = rand() % strlen($chars);
      $tmp = substr($chars, $num, 1);
      $pass .= $tmp;
    }

    return $pass;
  }

  /**
   * Returns the argument.
   * This can be useful to pass around as a callback.
   *
   * @param mixed $arg
   *   The argument.
   * @return mixed
   *   The argument, returned.
   */
  static function identity($arg) {
    return $arg;
  }

  static function identityCallback($arg) {
    return array(new xautoload_Container_Identity($arg), 'get');
  }

  static function containerCallback($container, $key) {
    return array(new xautoload_Container_MagicGet($container, $key), 'get');
  }

  static function callbackToString($callback) {
    if (is_array($callback)) {
      list($obj, $method) = $callback;
      if (is_object($obj)) {
        $str = get_class($obj) . '->' . $method . '()';
      }
      else {
        $str = $obj . '::';
        $str .= $method . '()';
      }
    }
    else {
      $str = $callback;
    }
    return $str;
  }
}

