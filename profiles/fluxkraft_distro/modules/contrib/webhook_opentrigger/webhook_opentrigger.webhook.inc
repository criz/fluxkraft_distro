<?php
/**
 * @file
 * webhook_opentrigger.webhook.inc
 */

/**
 * Implements hook_webhook_default_config().
 */
function webhook_opentrigger_webhook_default_config() {
  $export = array();

  $webhook = new stdClass();
  $webhook->disabled = FALSE; /* Edit this to true to make a default webhook disabled initially */
  $webhook->api_version = 1;
  $webhook->whid = '1';
  $webhook->title = 'OpenTrigger';
  $webhook->machine_name = 'opentrigger';
  $webhook->description = 'Providing OpenTrigger webhook support for the Webhook module.';
  $webhook->unserializer = 'opentrigger';
  $webhook->processor = 'opentriggerevent';
  $webhook->config = '';
  $webhook->enabled = TRUE;
  $export['opentrigger'] = $webhook;

  return $export;
}
