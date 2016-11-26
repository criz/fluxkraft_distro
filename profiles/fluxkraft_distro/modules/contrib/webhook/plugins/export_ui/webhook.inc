<?php

$plugin = array(
  'schema' => 'webhook',
  'menu' => array(
    'menu prefix' => 'admin/config/services',
    'menu item' => 'webhook',
    'menu title' => 'Webhooks',
    'menu description' => 'Administer webhooks.',
  ),
  'title singular' => t('webhook'),
  'title singular proper' => t('Webhook'),
  'title plural' => t('webhooks'),
  'title plural proper' => t('Webhooks'),
  'form' => array(
    'settings' => 'webhook_form',
    'validate' => 'webhook_form_validate',
    'submit' => 'webhook_form_submit',
  ),

);

/**
 * Form for editing a webhook.
 */
function webhook_form(&$form, &$form_state) {

  $webhook = $form_state['item'];

  $form['whid'] = array(
    '#type' => 'value',
    '#value' => $webhook->whid,
  );

  $form['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#description' => t('The name of the webhook.'),
    '#default_value' => $webhook->title,
    '#maxlength' => 100,
    '#required' => TRUE,
  );

  $form['description'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#default_value' => $webhook->description,
    '#description' => t('Descrive the webhook.'),
  );

  $form['unserializer'] = array(
    '#type' => 'select',
    '#title' => t('Unserializer'),
    '#default_value' => $webhook->unserializer,
    '#options' => webhook_get_unserlizer_list(),
    '#required' => TRUE,
    '#description' => t('Select plugin to be used to unserialize the request data.'),
  );

  $form['processor'] = array(
    '#type' => 'select',
    '#title' => t('Processor'),
    '#default_value' => $webhook->processor,
    '#options' => webhook_get_processor_list(),
    '#required' => TRUE,
    '#description' => t('Select plugin to be used to process the data from the request.'),
  );

  $form['processor_config'] = array(
    // Future use for Ajax forms.
  );

  $form['enabled'] = array(
    '#type' => 'select',
    '#title' => t('Enabled'),
    '#default_value' => (int) $webhook->enabled,
    '#options' => array(t('No'), t('Yes')),
    '#required' => TRUE,
    '#description' => t('Is this webhook enabled?'),
  );

  return $form;
}

/**
 * Validates the webhook form submission.
 */
function webhook_form_validate(&$form, &$form_state) {
  $webhook = webhook_load($form_state['values']['machine_name']);
  if ($webhook && $form_state['item']->whid != $webhook->whid) {
    form_set_error('machine_name', t('Machine Name already in use.'));
  }
}

/**
 * Handles the submission of the webhook edit form.
 */
function webhook_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];
  $keys = array('title', 'machine_name', 'description', 'unserializer', 'processor', 'enabled');
  $webhook = array();
  foreach ($keys as $key) {
    $webhook[$key] = $values[$key];
  }

  // We don't currently use config.
  $webhook['config'] = '';

  if (!empty($values['whid'])) {
    $webhook['whid'] = $values['whid'];
    // hack to stop duplicates.
    $webhook['export_type'] = EXPORT_IN_DATABASE;
  }

  $form_state['item'] = (object) $webhook;
}
