<?php

/**
 * @file
 * Hooks provided by X Autoload.
 */


/**
 * Implements hook_xautoload()
 *
 * Register additional classes, namespaces, autoload patterns, that are not
 * already registered by default.
 *
 * @param xautoload_InjectedAPI_hookXautoload $api
 *
 *   Object with a number of methods, which are documented at the class
 *   definition of xautoload_InjectedAPI_hookXautoload.
 *
 *   The object already knows which module we are at, so we don't need
 *   drupal_get_path().
 *
 *   TODO: The $api object should be specified by an interface.
 */
function hook_xautoload($api) {

  // Declare a foreign namespace in (module dir)/lib/ForeignNamespace/
  $api->namespaceRoot('ForeignNamespace');

  // Declare a foreign namespace in (module dir)/vendor/ForeignNamespace/
  $api->namespaceRoot('ForeignNamespace', 'vendor');

  // Declare a foreign namespace in /home/username/lib/ForeignNamespace/,
  // setting the $relative argument to FALSE.
  $api->namespaceRoot('ForeignNamespace', '/home/username/lib', FALSE);
}


/**
 * Implements hook_libraries_info()
 *
 * Allows to register PSR-0 (or other) class folders for your libraries.
 * (those things living in sites/all/libraries)
 *
 * The original documentation for this hook is at libraries module,
 * libraries.api.php
 *
 * X Autoload extends the capabilities of this hook, by adding an "xautoload"
 * key. This key takes a callback or closure function, which has the same
 * signature as hook_xautoload($api).
 * This means, you can use the same methods on the $api object.
 *
 * TODO: The $api object should be specified by an interface.
 *
 * @return array
 *   Same as explained in libraries module, but with added key 'xautoload'.
 */
function mymodule_libraries_info() {

  return array(
    'example-lib' => array(
      'name' => 'Example library',
      'vendor url' => 'http://www.example.com',
      'download url' => 'http://github.com/example/my-php-api',
      'version' => '1.0',
      'xautoload' => function($api) {
        // Register a namespace with PSR-0 root in <library dir>/lib/.
        // The second argument is relative to the directory of the library, so
        // PSR-0 root will be e.g. "sites/all/libraries/example-lib/lib".
        $api->namespaceRoot('ExampleVendor\ExampleLib', 'lib');
      },
    ),
  );
}
