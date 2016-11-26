<?php

/**
 * @file
 * Plugin class for processing OpenTrigger Events
 */

/**
 * Webhook Processor OpenTrigger class.
 *
 * Processes OpenTrigger events.
 */
class Webhook_Opentrigger_Plugins_Processor_OpentriggerEvent implements Webhook_Plugins_Processor_Interface {

  /**
   * Configuration form.
   */
  public function config_form() {
    // No configuration needed.
    return array();
  }

  /**
   * Processes data.
   */
  public function process(stdClass $data) {

    // Create a watchdog entry.
    $level = WATCHDOG_INFO;
    $message = 'New OpenTrigger event @UniqueIdentifier at @Timestamp (@EventType).';
    $vars = array(
      '@UniqueIdentifier' => $data->UniqueIdentifier,
      '@EventType' => $data->EventType,
      '@Timestamp' => $data->Timestamp
    );
    watchdog('webhook_opentrigger_event', $message, $vars, $level);

    // Create a rules event.
    $rules_event = ($data->EventType == 'Trigger') ? 'opentrigger_trigger_event' : 'opentrigger_release_event';
    rules_invoke_event($rules_event, array(
        'type' => $data->EventType,
        'button_id' => $data->UniqueIdentifier,
        'date' => $data->Timestamp,
        'origin_event_id' => $data->EventId,
      )
    );
  }
}
