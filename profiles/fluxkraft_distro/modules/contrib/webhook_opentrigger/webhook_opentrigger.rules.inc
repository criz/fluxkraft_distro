<?php

/**
 * @file
 * Rules integration.
 */

/**
 * Implements hook_rules_event_info().
 */
function webhook_opentrigger_rules_event_info() {
  $info = array(
    'opentrigger_trigger_event' => array(
      'label' => t('OpenTrigger Trigger Event'),
      'group' => t('OpenTrigger'),
      'variables' => array(
        'opentrigger_button' => array('type' => 'opentrigger_button', 'label' => t('OpenTrigger Button Event Info'))
      ),
    ),
    'opentrigger_release_event' => array(
      'label' => t('OpenTrigger Release Event'),
      'group' => t('OpenTrigger'),
      'variables' => array(
        'opentrigger_button' => array('type' => 'opentrigger_button', 'label' => t('OpenTrigger Button Event Info'))
      )
    )
  );
  return $info;
}

/**
 * Implements hook_rules_data_info().
 * @see rules_core_modules()
 */
function webhook_opentrigger_rules_data_info() {
  return array(
    'opentrigger_button' => array(
      'label' => t('OpenTrigger Button event info'),
      'wrap' => TRUE,
      'group' => 'OpenTrigger',
      'property info' => _webhook_opentrigger_button_event_info(),
    )
  );
}

/**
 * Defines property info for button event info, used by the data
 * type to provide an useful metadata wrapper.
 */
function _webhook_opentrigger_button_event_info() {
  return array(
    'type' => array(
      'type' => 'text',
      'label' => t('OpenTrigger event type'),
      'description' => t('Type of the button event (e.g. "Trigger" or "Release").')
    ),
    'button_id' => array(
      'type' => 'text',
      'label' => t('Button ID'),
      'description' => t('Identifier of the button that triggered the event.')
    ),
    'date' => array(
      'type' => 'date',
      'label' => t('Date'),
      'description' => t('Event date and time.')
    ),
    'origin_event_id' => array(
      'type' => 'text',
      'label' => t('Origin Event ID'),
      'description' => t('Id of the origin OpenTrigger Event. Pressing a button includes a Trigger Event and a Release Event, where both would have the same origin event id.'),
    )
  );
}
