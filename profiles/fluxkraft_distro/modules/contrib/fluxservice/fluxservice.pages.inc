<?php

/**
 * @file
 * Contains page and form callbacks.
 */

use Drupal\fluxservice\Plugin\Entity\AccountInterface;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;
use Drupal\fluxservice\UI\ServiceUIController;
use Drupal\fluxservice\UI\AccountUIController;
use Drupal\fluxservice\Entity\PluginConfigEntityBase;

/**
 * ServiceInterface configuration entity form callback.
 */
function fluxservice_service_form($form, &$form_state, ServiceInterface $service) {
  $form += fluxservice_service_form_defaults($form_state, $service);

  $form['actions'] = array(
    '#type' => 'actions',
    '#weight' => 10,
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
  );

  $form['actions']['delete'] = array(
    '#type' => 'submit',
    '#value' => t('Delete'),
    '#access' => !$service->isNew(),
    '#submit' => array('fluxservice_service_delete_submit'),
  );

  return $form;
}

/**
 * Form validation callback for service entities.
 */
function fluxservice_service_form_validate($form, &$form_state) {
  // Invoke the plugin specific validation handler.
  $service = $form_state['fluxservice_service'];
  $service->settingsFormValidate($form, $form_state);
}

/**
 * Form submission callback for saving service entities.
 */
function fluxservice_service_form_submit($form, &$form_state) {
  $service = fluxservice_form_submit_build_entity($form, $form_state);

  // Invoke the plugin specific form submit handler.
  $service->settingsFormSubmit($form['data'], $form_state);
  $service->save();

  // Redirect to the overview page after editing the entity.
  $form_state['redirect'] = $form_state['controller']->getPath();
  drupal_set_message(t('The configuration options have been saved.'));
}

/**
 * Helper function for building the service form.
 */
function fluxservice_service_form_defaults(array &$form_state, ServiceInterface $service) {

  $form = array(
    '#tree' => TRUE,
  );

  $form['label'] = array(
    '#type' => 'textfield',
    '#title' => t('Label'),
    '#description' => t('The label of the service.'),
    '#default_value' => $service->label(),
    '#required' => TRUE,
    '#size' => 32,
    '#maxlength' => 64,
  );

  // Track which properties should be extracted afterwards.
  // See fluxservice_form_submit_build_entity().
  $form_state['properties'] = array('label');

  // Build the plugin specific form.
  $form['data'] = $service->settingsForm($form_state);

  return $form;
}

/**
 * Form submission callback for the delete button on the service entity form.
 */
function fluxservice_service_delete_submit($form, &$form_state) {
  // Remove the destination query parameter (if set) as we definitely want to
  // redirect to the deletion confirm form.
  $options = array();
  if (isset($_GET['destination']) && $destination = $_GET['destination']) {
    $options = array('query' => array('destination' => $destination));
    unset($_GET['destination']);
  }

  $identifier = $form_state['fluxservice_service']->identifier();
  $form_state['redirect'] = array($form_state['controller']->getPath("manage/$identifier/delete"), $options);
}

/**
 * Form submission callback for the delete button on the service entity form.
 */
function fluxservice_service_delete_confirm($form, &$form_state, ServiceInterface $service, ServiceUIController $controller) {
  $form_state['fluxservice_service'] = $service;
  $form_state['controller'] = $controller;

  return confirm_form($form, t('Are you sure you want to delete the service %service?', array(
    '%service' => $service->label(),
  )), $form_state['controller']->getPath(), 'This operation cannot be undone.', t('Delete'), t('Cancel'));
}

/**
 * Form submission callback for the delete button on the service entity form.
 */
function fluxservice_service_delete_confirm_submit($form, &$form_state) {
  $form_state['fluxservice_service']->delete();
  $form_state['redirect'] = $form_state['controller']->getPath();
}

/**
 * AccountInterface configuration entity form callback.
 */
function fluxservice_account_form($form, &$form_state, AccountInterface $account) {
  $form += fluxservice_account_form_defaults($form_state, $account);

  $form['actions'] = array(
    '#type' => 'actions',
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
  );

  $form['actions']['delete'] = array(
    '#type' => 'submit',
    '#value' => t('Delete'),
    '#access' => !$account->isNew(),
    '#submit' => array('fluxservice_account_delete_submit'),
    '#limit_validation_errors' => array(),
  );

  return $form;
}

/**
 * Helper function for building the account form.
 */
function fluxservice_account_form_defaults(array &$form_state, AccountInterface $account) {

  $form = array(
    '#tree' => TRUE,
  );

  $form['label'] = array(
    '#type' => 'textfield',
    '#title' => t('Label'),
    '#description' => t('The label of the account.'),
    '#default_value' => $account->label(),
    '#required' => TRUE,
    '#size' => 32,
    '#maxlength' => 64,
  );

  // Track which properties should be extracted afterwards.
  // See fluxservice_form_submit_build_entity().
  $form_state['properties'] = array('label');

  // Build the plugin specific form.
  $form['data'] = $account->settingsForm($form_state);

  return $form;
}

/**
 * Form validation callback for account entities.
 */
function fluxservice_account_form_validate($form, &$form_state) {
  // Invoke the plugin specific validation handler.
  $account = $form_state['fluxservice_account'];
  $account->settingsFormValidate($form['data'], $form_state);
}

/**
 * Form submission callback for saving account entities.
 */
function fluxservice_account_form_submit($form, &$form_state) {
  $account = fluxservice_form_submit_build_entity($form, $form_state);

  // Invoke the plugin specific form submit handler.
  $account->settingsFormSubmit($form['data'], $form_state);
  $account->save();

  // Redirect the user to the account overview.
  $form_state['redirect'] = $form_state['controller']->getPath();
  drupal_set_message(t('The configuration options have been saved.'));
}

/**
 * Form submission callback for the delete button on the account entity form.
 */
function fluxservice_account_delete_submit($form, &$form_state) {
  $account = $form_state['fluxservice_account'];

  // Remove the destination query parameter (if set) as we definitely want to
  // redirect to the deletion confirm form.
  $options = array();
  if (isset($_GET['destination']) && $destination = $_GET['destination']) {
    $options = array('query' => array('destination' => $destination));
    unset($_GET['destination']);
  }

  $form_state['redirect'] = array($form_state['controller']->getPath("manage/{$account->identifier()}/delete"), $options);
}

/**
 * Form submission callback for the delete button on the account entity form.
 */
function fluxservice_account_delete_confirm($form, &$form_state, AccountInterface $account, AccountUIController $controller) {
  $form_state['fluxservice_account'] = $account;
  $form_state['controller'] = $controller;

  return confirm_form($form, t('Are you sure you want to delete the account %account?', array(
    '%account' => $account->label(),
  )), $controller->getPath(), 'This operation cannot be undone.', t('Delete'), t('Cancel'));
}

/**
 * Form submission callback for the delete button on the account entity form.
 */
function fluxservice_account_delete_confirm_submit($form, &$form_state) {
  $account = $form_state['fluxservice_account'];

  // Delete the account.
  $account->delete();

  // Redirect the the user to the account overview.
  $form_state['redirect'] = $form_state['controller']->getPath();
}

/**
 * A more useful variant of entity_submit_build_entity().
 */
function fluxservice_form_submit_build_entity($form, &$form_state) {
  $entity_type = $form_state['entity_type'];
  $entity = $form_state[$entity_type];

  foreach (array_intersect_key($form_state['values'], array_flip($form_state['properties'])) as $key => $value) {
    $entity->$key = $value;
  }

  // Invoke all specified builders for copying form values to entity properties.
  if (isset($form['#entity_builders'])) {
    foreach ($form['#entity_builders'] as $function) {
      $function($entity_type, $entity, $form, $form_state);
    }
  }

  // Copy field values to the entity.
  $info = entity_get_info($entity_type);
  if ($info['fieldable']) {
    field_attach_submit($entity_type, $entity, $form, $form_state);
  }
  return $entity;
}
