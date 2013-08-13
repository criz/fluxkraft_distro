<?php

namespace Drupal\xautoload\Tests;

class XAutoloadUnitTestCase extends \DrupalUnitTestCase {

  static function getInfo() {
    return array(
      'name' => 'X Autoload unit test',
      'description' => 'Test the xautoload class finder.',
      'group' => 'X Autoload',
    );
  }

  function assertPublic($status, $message) {
    return $this->assert($status, $message);
  }

  function setUp() {

    // drupal_load('module', 'xautoload') would register namespaces for all
    // enabled modules, which is not intended for this unit test.
    // Instead, we just include xautoload.early.inc.
    require_once dirname(__FILE__) . '/../../../../xautoload.early.inc';

    // Make sure we use the regular loader, not the APC one.
    // Also make sure to prepend this one. Otherwise, the core class loader will
    // try to load xautoload-related stuff, e.g. xautoload_Mock_* stuff, and
    // will fail due to the database.
    xautoload('loaderManager')->register('default', TRUE);

    // Do the regular setUp().
    parent::setUp();
  }

  function testAutoloadStackOrder() {
    $expected = array(
      'xautoload_ClassLoader_NoCache->loadClass()',
      'drupal_autoload_class',
      'drupal_autoload_interface',
      '_simpletest_autoload_psr0',
    );

    $msg = 'spl_autoload_functions():';
    foreach (spl_autoload_functions() as $index => $callback) {
      $str = $this->callbackToString($callback);
      if (!isset($expected[$index])) {
        $this->fail("Autoload callback at index $index must be empty instead of $str.");
      }
      else {
        $expected_str = $expected[$index];
        if ($expected_str === $str) {
          $this->pass("Autoload callback at index $index must be $expected_str.");
        }
        else {
          $this->fail("Autoload callback at index $index must be $expected_str instead of $str.");
        }
      }
    }
  }

  protected function callbackToString($callback) {
    if (is_array($callback)) {
      list($obj, $method) = $callback;
      if (is_object($obj)) {
        $str = get_class($obj) . '->' . $method . '()';
      }
      else {
        $str = $obj . '::' . $method . '()';
      }
    }
    else {
      $str = $callback;
    }
    return $str;
  }

  function testNamespaces() {

    // Prepare the class finder.
    $finder = new \xautoload_ClassFinder_NamespaceOrPrefix();
    $finder->registerNamespaceDeep('Drupal\\ex_ample', 'sites/all/modules/contrib/ex_ample/lib');
    $finder->registerNamespaceRoot('Drupal\\ex_ample', 'sites/all/modules/contrib/ex_ample/vendor');

    // Test class finding for 'Drupal\\ex_ample\\Abc_Def'.
    $this->assertFinderSuggestions($finder, 'Drupal\\ex_ample\\Abc_Def', array(
      // Class finder is expected to suggest these files, in the exact order,
      // until one of them is accepted.
      'sites/all/modules/contrib/ex_ample/lib/Abc/Def.php',
      'sites/all/modules/contrib/ex_ample/vendor/Drupal/ex_ample/Abc/Def.php',
    ));
  }

  function testPrefixes() {

    // Prepare the class finder.
    $finder = new \xautoload_ClassFinder_NamespaceOrPrefix();
    $finder->registerPrefixDeep('ex_ample', 'sites/all/modules/contrib/ex_ample/lib');
    $finder->registerPrefixRoot('ex_ample', 'sites/all/modules/contrib/ex_ample/vendor');

    // Test class finding for 'ex_ample_Abc_Def'.
    $this->assertFinderSuggestions($finder, 'ex_ample_Abc_Def', array(
      // Class finder is expected to suggest these files, in the exact order,
      // until one of them is accepted.
      'sites/all/modules/contrib/ex_ample/lib/Abc/Def.php',
      'sites/all/modules/contrib/ex_ample/vendor/ex/ample/Abc/Def.php',
    ));
  }

  protected function assertFinderSuggestions($finder, $class, array $expectedSuggestions) {
    for ($iAccept = 0; $iAccept < count($expectedSuggestions); ++$iAccept) {
      $api = new \xautoload_Mock_InjectedAPI_findFile($this, $class, $expectedSuggestions, $iAccept);
      $finder->findFile($api, $class);
      $api->finish();
    }
    $api = new \xautoload_Mock_InjectedAPI_findFile($this, $class, $expectedSuggestions);
    $finder->findFile($api, $class);
    $api->finish();
    $this->assert(TRUE, "Successfully loaded $class");
  }
}
