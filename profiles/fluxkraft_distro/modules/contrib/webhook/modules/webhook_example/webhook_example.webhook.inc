<?php
/**
 * @file
 * webhook_example.webhook.inc
 */

/**
 * Implements hook_webhook_default_config().
 */
function webhook_example_webhook_default_config() {
  $export = array();

  $webhook = new stdClass();
  $webhook->disabled = FALSE; /* Edit this to true to make a default webhook disabled initially */
  $webhook->api_version = 1;
  $webhook->whid = '1';
  $webhook->title = 'Github Example';
  $webhook->machine_name = 'github';
  $webhook->description = 'Example webhook for logging github commits to watchdog.';
  $webhook->unserializer = 'github';
  $webhook->processor = 'githublog';
  $webhook->config = '';
  $webhook->enabled = TRUE;
  $export['github'] = $webhook;

  return $export;
}
