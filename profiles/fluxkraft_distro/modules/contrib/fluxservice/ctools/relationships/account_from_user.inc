<?php

/**
 * @file
 * Plugin to provide an relationship handler for service account from user.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('flux Service account from user'),
  'keyword' => 'fluxservice_account',
  'description' => t('Retrieves a random flux service account from a user.'),
  'required context' => new ctools_context_required(t('User'), 'user'),
  'context' => 'ctools_account_from_user',
  'edit form' => 'ctools_account_from_user_settings_form',
  'defaults' => array('plugin' => NULL),
);

/**
 * Return a new context based on an existing context.
 */
function ctools_account_from_user($context, $conf) {

  if (!empty($context->data) && isset($context->data->uid)) {
    // Load the user that is the author of the node.
    $account = fluxservice_get_account_per_user($conf['plugin'], $context->data->uid);
    return ctools_context_create('fluxservice_account', $account);
  }

  // If unset it wants a generic, unfilled context, which is just NULL.
  // If nothing can be loaded, also.
  return ctools_context_create_empty('fluxservice_account', NULL);
}

/**
 * Settings form for the relationship.
 */
function ctools_account_from_user_settings_form($form, &$form_state) {
  $conf = $form_state['conf'];

  $form['plugin'] = array(
    '#type' => 'select',
    '#title' => t('Account type'),
    '#description' => t('Select what kind of service account is to be retrieved.'),
    '#options' => fluxservice_account_plugin_options(),
    '#default_value' => $conf['plugin'],
  );

  return $form;
}
