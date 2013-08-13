<?php

/**
 * @file
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * Specifies directories for class-based plugin discovery.
 *
 * See fluxservice_fluxservice_plugin_type_info() for plugin types supported
 * by the fluxservice module and refer to interfaces specified there for further
 * documentation.
 *
 * @return string|array
 *   A directory relative to the module directory, which holds the files
 *   containing rules plugin handlers, or multiple directories keyed by the
 *   module the directory is contained in.
 *   All files in those directories having a 'php' or 'inc' file extension will
 *   be loaded during discovery. Optionally, wildcards ('*') may be used to
 *   match multiple directories.
 *
 * @see fluxservice_get_plugin_directories()
 * @see fluxservice_fluxservice_plugin_type_info()
 */
function hook_fluxservice_plugin_directory() {
  return 'lib/Drupal/fluxtwitter/Plugin';
}

/**
 * Define fluxservice plugin types.
 *
 * @return array
 *   An array of plugin type definitions, keyed by plugin type name. For each
 *   type a sub-array with the following keys has to be specified:
 *   - interface: The interface all plugins must implement. Only plugins with
 *     that interface will be discovered.
 *   - directory: A sub-directory below the fluxservice plugin directory in
 *     which plugins will be discovered.
 *     See hook_fluxservice_plugin_directory().
 *   - (optional) The alter hook to invoke for customizing plugin definitions.
 *     Defaults to "$type_plugin_info".
 */
  function hook_fluxservice_plugin_type_info() {
  return array(
    'fluxservice_account' => array(
      'interface' => 'Drupal\fluxservice\Plugin\AccountInterface',
      'directory' => 'Service',
    ),
    'fluxservice_service' => array(
      'interface' => 'Drupal\fluxservice\ServiceInterface',
      'directory' => 'Service',
    ),
  );
}

/**
 * Control access to fluxservice accounts.
 *
 * Modules may implement this hook if they want to have a say in whether or not
 * a given user has access to perform a given operation on an account.
 *
 * @param string $op
 *   The operation being performed. One of 'view', 'create', 'update', 'delete'
 *   or 'use'.
 * @param \Drupal\fluxservice\Plugin\Entity\Account $fluxservice_account
 *   (optional) An account to check access for. If nothing is given,
 *   access for all accounts is determined.
 * @param stdclass $account
 *   (optional) The user to check for. If no account is passed, access is
 *   determined for the current user.
 *
 * @return boolean
 *   Return TRUE to grant access, FALSE to explicitly deny access. Return NULL
 *   or nothing to not affect the operation.
 *   Access is granted as soon as a module grants access and no one denies
 *   access. Thus if no module explicitly grants access, access will be denied.
 *
 * @see fluxservice_account_access()
 */
function hook_fluxservice_account_access($op, \Drupal\fluxservice\Plugin\Entity\Account $fluxservice_account = NULL, $account = NULL) {
  // Instead of returning FALSE return nothing, so others still can grant
  // access.
  if (isset($fluxservice_account) && $fluxservice_account->owner == 'mymodule' && user_access('my modules permission')) {
    return TRUE;
  }
}
