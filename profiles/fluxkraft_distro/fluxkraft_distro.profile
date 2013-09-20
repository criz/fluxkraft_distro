<?php

/**
 * @file
 * Enables modules and site configuration for the fluxkraft distribution.
 */

/**
 * Implements hook_form_alter().
 *
 * Allows the profile to alter the site configuration form.
 */
function fluxkraft_distro_form_install_configure_form_alter(&$form, $form_state) {
  // Set a default name for the dev site.
  $form['site_information']['site_name']['#default_value'] = t('fluxkraft');
}

/**
 * Installation step for checking critical requirements before continuing.
 *
 * This is necessary because the next step(s) in the installer already require
 * parts of the API in order to be able to configure endpoints, etc.
 */
function fluxkraft_distro_verify_requirements($install_state) {
  // Some modules have hook_requirements() in their .install file. Hence, we
  // need to load all the .install files as well.
  drupal_load_updates();

  $requirements = module_invoke_all('requirements', 'runtime');
  $requirements = array_filter($requirements, function ($requirement) {
    return isset($requirement['severity']) && $requirement['severity'] === REQUIREMENT_ERROR;
  });

  // If there are no errors, continue.
  if (empty($requirements)) {
    return;
  }

  // If there are errors, always display them.
  drupal_set_title(st('Requirements problem'));
  $status = theme('status_report', array('requirements' => $requirements));
  $status .= st('Check the error messages and <a href="!url">proceed with the installation</a>.', array('!url' => check_url(drupal_requirements_url(REQUIREMENT_ERROR))));
  return $status;
}

/**
 * Installation step form callback.
 */
function fluxkraft_distro_configure_endpoints_form($form, &$form_state) {
  form_load_include($form_state, 'inc', 'fluxservice', 'fluxservice.pages');

  if (!empty($form_state['rebuild'])) {
    // The install does not care about form rebuilds, so we have to trick it
    // into thinking this is the first form run for the rebuild to apply.
    $form_state['executed'] = FALSE;
  }
  else {
    // Also, we do not have a cache system setup (but just a fake cache) so
    // form state won't be persisted as usual. Be sure to track state in our own
    // state passed on as hidden input.
    // Note that this state could be manipulated client-side but we do not store
    // sensitive information in there.
    $form_state['state'] = isset($form_state['input']['state']) ? unserialize($form_state['input']['state']) : array();
  }

  if (!empty($form_state['state']['plugin'])) {
    $form = fluxkraft_distro_add_endpoint_form($form, $form_state);
  }
  else {
    $form = fluxkraft_distro_add_endpoint_overview_form($form, $form_state);
  }

  $form['state'] = array(
    '#type' => 'hidden',
    '#value' => serialize($form_state['state']),
  );
  return $form;
}

/**
 * Installation step form callback.
 */
function fluxkraft_distro_configure_cron_form($form, &$form_state) {
  drupal_set_title(st('Configure cron'));
  $cron_key = variable_get('cron_key', 'drupal');
  $cron_url = url('cron.php', array('absolute' => TRUE, 'query' => array('cron_key' => $cron_key)));

  $form['help'] = array(
    '#markup' => t('<p>You need to configure cron to allow fluxkraft to work properly.</p>
    <p>If you have not configured it already, you can find detailed instructions
    at !url or at step 8 of the Drupal INSTALL.txt file that is included with fluxkraft.</p>
    <p>Summarized for Unix/Linux based systems, the following crontab line configures
     cron as required and runs it every 5 minutes:</p>
    <code>!code</code><p>We recommened running cron every 5 minutes!</p>', array(
      '!code' => '*/5 * * * * wget -O - -q -t 1 ' . $cron_url,
      '!url' => l('https://drupal.org/cron', 'https://drupal.org/cron', array(
        'external' => TRUE,
        'attributes' => array('target' => '_blank'),
      )),
    )),
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => st('Continue'),
  );
  return $form;
}

/**
 * Form builder for the overview form.
 */
function fluxkraft_distro_add_endpoint_overview_form($form, &$form_state) {
  drupal_set_title(st('Configure services'));

  foreach (fluxservice_get_service_plugin_info() as $plugin => $info) {
    // Do not show UI for adding feeds here.
    if ($plugin == 'fluxfeed') {
      continue;
    }
    $form['#prefix'] = '<div class="description">' . st('Here you can already configure some of your services. Note that you can configure your services later also!') . '</div><br />';
    $form[$plugin] = array(
      '#theme_wrappers' => array('fluxservice_icon'),
    );
    // Set icon info in the render array, so it gets passed through.
    foreach (fluxservice_get_service_icon_info($plugin) as $key => $value) {
      $form[$plugin]['#' . $key] = $value;
    }
    // Adding some info as title attribute.
    $form[$plugin]['#attributes']['title'][] = st('Configure your') . ' ' . $info['label'];
    if (!empty($form_state['state']['plugins_added'][$plugin])) {
      $form[$plugin]['#attributes']['class'][] = 'fluxservice-created';
    }
    else {
      $form[$plugin]['button'] = array(
        '#type' => 'submit',
        '#value' => $info['label'],
        '#name' => $plugin,
        '#plugin' => $plugin,
      );
    }
  }

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => st('Continue'),
  );
  $form['#submit'][] = 'fluxkraft_distro_add_endpoint_overview_form_submit';
  return $form;
}

/**
 * Form submit callback for creating service instances.
 */
function fluxkraft_distro_add_endpoint_overview_form_submit($form, &$form_state) {
  if (!empty($form_state['triggering_element']['#plugin'])) {
    $form_state['state']['plugin'] = $form_state['triggering_element']['#plugin'];
    $form_state['rebuild'] = TRUE;
  }
}

/**
 * Form builder for adding a single endpoint form.
 */
function fluxkraft_distro_add_endpoint_form($form, &$form_state) {
  $info = fluxservice_get_plugin_info('fluxservice_service', $form_state['state']['plugin']);
  drupal_set_title(st('Configure @service', array('@service' => $info['label'])));

  $form_state['entity_type'] = 'fluxservice_service';
  $form_state['fluxservice_service'] = entity_create('fluxservice_service', array('plugin' => $form_state['state']['plugin']));

  $form += fluxservice_service_form_defaults($form_state, $form_state['fluxservice_service']);
  // Always hide the rules specific additions.
  $form['data']['rules']['#access'] = FALSE;

  $form['actions'] = array('#type' => 'actions');

  $form['actions']['cancel'] = array(
    '#type' => 'submit',
    '#value' => st('Cancel'),
    '#limit_validation_errors' => array(),
    '#submit' => array('fluxkraft_distro_add_endpoint_cancel_form_submit'),
  );
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => st('Save'),
    '#validate' => array('fluxservice_service_form_validate'),
    '#submit' => array('fluxkraft_distro_add_endpoint_form_submit'),
  );

  return $form;
}

/**
 * Form submit callback for creating service instances.
 */
function fluxkraft_distro_add_endpoint_form_submit($form, &$form_state) {
  $service = fluxservice_form_submit_build_entity($form, $form_state);

  // Invoke the plugin specific form submit handler.
  $service->settingsFormSubmit($form['data'], $form_state);
  $service->save();

  $form_state['rebuild'] = TRUE;
  $form_state['state']['plugins_added'][$form_state['state']['plugin']] = TRUE;
  unset($form_state['state']['plugin']);
}

/**
 * Form submit callback for cancel button.
 */
function fluxkraft_distro_add_endpoint_cancel_form_submit($form, &$form_state) {
  $form_state['rebuild'] = TRUE;
  unset($form_state['state']['plugin']);
}
