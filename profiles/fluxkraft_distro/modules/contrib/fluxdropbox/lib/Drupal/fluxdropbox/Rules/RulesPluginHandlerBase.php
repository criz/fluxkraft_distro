<?php

/**
 * @file
 * Contains RulesPluginHandlerBase.
 */

namespace Drupal\fluxdropbox\Rules;

use Drupal\fluxservice\Rules\FluxRulesPluginHandlerBase;

/**
 * Base class for dropbox Rules plugin handler.
 */
abstract class RulesPluginHandlerBase extends FluxRulesPluginHandlerBase {

  /**
   * Returns info-defaults for dropbox plugin handlers.
   */
  public static function getInfoDefaults() {
    return array(
      'category' => 'fluxdropbox',
      'access callback' => array(get_called_class(), 'integrationAccess'),
    );
  }

  /**
   * Rules dropbox integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxdropbox');
  }

  /**
   * Returns info suiting for dropbox service account parameters.
   */
  public static function getAccountParameterInfo() {
    return array(
      'type' => 'fluxservice_account',
      'bundle' => 'fluxdropbox',
      'label' => t('Dropbox account'),
      'description' => t('The Dropbox account under which this shall be executed.'),
    );
  }
}
