<?php

/**
 * @file
 * Webhook module administration functions.
 */

/**
 * Callback for webhook admin page.
 */
function webhook_configure() {

  $header = array(
    t('Name'),
    t('URL'),
    array('data' => t('Operations'), 'colspan' => 2),
  );

  $rows = array();
  $webhooks = webhook_load_all();
  if (!count($webhooks)) {
    $rows[] = array(
      array(
        'data' => t('No webhooks. !create', array('!create' => l('Create a webhook.', 'admin/config/services/webhook/add'))),
        'colspan' => 4,
      ),
    );
  }
  else {
    foreach ($webhooks as $webhook) {
      $row = array(
        check_plain($webhook->title),
        check_plain($webhook->machine_name),
        l(t('Edit'), "admin/config/services/webhook/{$webhook->whid}/edit"),
        l(t('Delete'), "admin/config/services/webhook/{$webhook->whid}/delete"),
      );
      $rows[] = $row;
    }
  }

  return theme('table', array('header' => $header, 'rows' => $rows));
}

/**
 * Callback for creating a webbook.
 */
function webhook_add() {
  $webhook = (object) array(
    'whid' => NULL,
    'title' => '',
    'machine_name' => '',
    'description' => '',
    'config' => '',
    'unserializer' => '',
    'processor' => '',
    'enabled' => TRUE,
  );

  return drupal_get_form('webhook_form', $webhook);
}

/**
 * Callback for creating a webhook edit form.
 */
function webhook_edit($whid) {
  $webhook = webhook_load($whid);

  if (!$webhook) {
    drupal_set_message(t('Invalid webhook.'));
    return drupal_goto('admin/config/services/webhook');
  }

  return drupal_get_form('webhook_form', $webhook);
}

/**
 * Form for editing a webhook.
 */
function webhook_form($form, $form_state, $webhook) {
  ctools_include('plugins');
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

  $form['machine_name'] = array(
    '#type' => 'machine_name',
    '#title' => t('Machine Name'),
    '#description' => t('Choose a unique identifier.  This can not be changed once set.  This will also be used as the URL.'),
    '#default_value' => $webhook->machine_name,
    '#maxlength' => 50,
    '#required' => TRUE,
    '#disabled' => !empty($webhook->title),
    '#machine_name' => array(
      'exists' => 'webhook_edit_machine_name_value_exists',
      'source' => array('title'),
    ),
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

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Save',
  );

  return $form;
}

/**
 * Handles the submission of the webhook edit form.
 */
function webhook_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  $keys = array('title', 'machine_name', 'description', 'unserializer', 'processor', 'enabled');
  $webhook = array();
  foreach ($keys as $key) {
    $webhook[$key] = $values[$key];
  }

  // We don't currently use config.
  $webhook['config'] = array('IGNORE ME');

  if (!empty($values['whid'])) {
    $webhook['whid'] = $values['whid'];
    webhook_update($webhook);
    drupal_set_message(t("Webhook '@title' updated.", array('@title' => $webhook['title'])));
  }
  else {
    webhook_insert($webhook);
    drupal_set_message(t("Webhook '@title' created.", array('@title' => $webhook['title'])));
  }

  $form_state['redirect'] = 'admin/config/services/webhook';
}

function webhook_edit_machine_name_value_exists($value, $element, &$form_state) {
  $webhook = webhook_load_by_name($value);
  return !empty($webhook);
}
