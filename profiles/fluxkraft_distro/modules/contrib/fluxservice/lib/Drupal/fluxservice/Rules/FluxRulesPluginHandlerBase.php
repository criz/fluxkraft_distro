<?php

/**
 * @file
 * Contains FluxRulesPluginHandlerBase.
 */

namespace Drupal\fluxservice\Rules;

/**
 * Base class for fluxservice related Rule actions.
 *
 * This class takes care of incorporating necessary access checks for using
 * selected service accounts.
 */
class FluxRulesPluginHandlerBase extends \RulesPluginHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function access() {
    if (!parent::access()) {
      return FALSE;
    }
    foreach ($this->element->pluginParameterInfo() as $name => $parameter_info) {
      if ($parameter_info['type'] == 'fluxservice_account' && !$this->checkAccountUsageAccess($name, $parameter_info)) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Checks usage permission for the given 'fluxservice_account' parameter.
   */
  protected function checkAccountUsageAccess($name, $parameter_info) {
    if (isset($this->element->settings[$name]) && $account = fluxservice_account_load($this->element->settings[$name])) {
      return fluxservice_account_access('use', $account);
    }
    $vars = $this->element->availableVariables();
    if (isset($this->element->settings[$name . ':select']) || isset($vars[$name])) {
      return fluxservice_account_access('use');
    }
    // Unconfigured is OK.
    return TRUE;
  }

}
