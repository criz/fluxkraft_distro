<?php

/**
 * @file
 * Contains AccountEntity.
 */

namespace Drupal\fluxservice\Rules\DataUI;

/**
 * DataUI class for adjust the Rules data UI for accounts.
 */
class AccountEntity extends \RulesDataUIEntityExportable implements \RulesDataInputOptionsListInterface {

  /**
   * {@inheritdoc}
   */
  public static function optionsList(\RulesPlugin $element, $name) {
    list(, $parameter_info) = \RulesDataUI::getTypeInfo($element, $name);

    // We need the bundle in order to generate options.
    if (empty($parameter_info['bundle'])) {
      return array();
    }
    return static::getOptions($parameter_info['bundle'], $element->root());
  }

  /**
   * Helper for getting service account options.
   *
   * This helper takes rule-ownership into account.
   */
  public static function getOptions($plugin, \RulesPlugin $rules_config) {
    // Show a list of accounts. If this rule configuration is personal,
    // only allow using personal accounts.
    $uid = isset($rules_config->uid) ? $rules_config->uid : NULL;
    return fluxservice_account_options($plugin, $uid);
  }

}
