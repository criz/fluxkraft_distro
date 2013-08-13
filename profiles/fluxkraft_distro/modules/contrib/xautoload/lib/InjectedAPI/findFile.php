<?php


/**
 * To help testability, we use an injected API instead of just a return value.
 * The injected API can be mocked to provide a mocked file_exists(), and to
 * monitor all suggested candidates, not just the correct return value.
 */
class xautoload_InjectedAPI_findFile {

  protected $file;
  protected $className;

  /**
   * @param $class_name
   *   Name of the class or interface we are trying to load.
   */
  function __construct($class_name) {
    $this->className = $class_name;
  }

  /**
   * This is done in the injected api object, so we can easily provide a mock
   * implementation.
   */
  function is_dir($dir) {
    return is_dir($dir);
  }

  /**
   * Get the name of the class we are looking for.
   *
   * @return string
   *   The class we are looking for.
   */
  function getClass() {
    return $this->className;
  }

  /**
   * Suggest a file that, if the file exists,
   * has to declare the class we are looking for.
   * Only keep the class on success.
   *
   * @param string $file
   *   The file that is supposed to declare the class.
   */
  function suggestFile($file) {
    if (file_exists($file)) {
      $this->file = $file;
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Same as suggestFile(), but skip the file_exists(),
   * assuming that we already know the file exists.
   *
   * This could make sense if a plugin already did the file_exists() check.
   *
   * @param string $file
   *   The file that is supposed to declare the class.
   */
  function suggestFile_skipFileExists($file) {
    $this->file = $file;
    return TRUE;
  }

  /**
   * Same as suggestFile(), but assume that file_exists() returns TRUE.
   *
   * @param string $file
   *   The file that is supposed to declare the class.
   */
  function suggestFile_checkNothing($file) {
    $this->file = $file;
    return TRUE;
  }

  /**
   * Same as suggestFile(), but check the full PHP include path.
   *
   * @param string $file
   *   The file that is supposed to declare the class.
   */
  function suggestFile_checkIncludePath($file) {
    if ($this->_fileExists_checkIncludePath($file)) {
      $this->file = $file;
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * When the process has finished, use this to return the result.
   *
   * @return string
   *   The file that is supposed to declare the class.
   */
  function getFile() {
    return $this->file;
  }

  /**
   * Check if a file exists, considering the full include path.
   *
   * @param string $file
   *   The filepath
   * @return boolean
   *   TRUE, if the file exists somewhere in include path.
   */
  protected function _fileExists_checkIncludePath($file) {
    if (function_exists('stream_resolve_include_path')) {
      // Use the PHP 5.3.1+ way of doing this.
      return (FALSE !== stream_resolve_include_path($file));
    }
    elseif ($file{0} === DIRECTORY_SEPARATOR) {
      // That's an absolute path already.
      return file_exists($file);
    }
    else {
      // Manually loop all candidate paths.
      foreach (explode(PATH_SEPARATOR, get_include_path()) as $base_dir) {
        if (file_exists($base_dir . DIRECTORY_SEPARATOR . $file)) {
          return TRUE;
        }
      }
      return FALSE;
    }
  }
}
