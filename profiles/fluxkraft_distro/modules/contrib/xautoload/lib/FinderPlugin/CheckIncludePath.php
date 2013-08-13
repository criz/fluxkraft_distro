<?php


/**
 * This class is not used anywhere in xautoload, but could be used by other
 * modules.
 */
class xautoload_FinderPlugin_CheckIncludePath implements xautoload_FinderPlugin_Interface {

  /**
   * Expect a class Aaa_Bbb_Ccc_Ddd to be in Aaa/Bbb/Ccc/Ddd.php,
   * but consider the PHP include_path setting.
   *
   * @param object $api
   *   The InjectedAPI object.
   * @param string $path_fragment
   *   First part of the path, for instance "Aaa/Bbb/".
   * @param string $path_suffix
   *   Second part of the path, for instance "Ccc/Ddd.php".
   */
  function findFile($api, $path_fragment, $path_suffix) {
    $path = $path_fragment . $path_suffix;
    if ($api->suggestFile_checkIncludePath($path)) {
      return TRUE;
    }
  }
}
