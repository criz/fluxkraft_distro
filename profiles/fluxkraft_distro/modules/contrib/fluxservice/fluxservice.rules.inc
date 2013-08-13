<?php

/**
 * @file
 * fluxservice Rules integration.
 */

/**
 * Implements hook_rules_data_info_alter()
 */
function fluxservice_rules_data_info_alter(&$data_info) {
  $data_info['fluxservice_account']['ui class'] = 'Drupal\fluxservice\Rules\DataUI\AccountEntity';
}
