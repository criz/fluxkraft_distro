<?php

/**
 * @file
 * Contains RulesPluginHandlerBase.
 */

namespace Drupal\fluxfeed\Rules;

use Drupal\fluxservice\Rules\FluxRulesPluginHandlerBase;

/**
 * Base class for feed plugin handlers.
 */
abstract class RulesPluginHandlerBase extends FluxRulesPluginHandlerBase {

  /**
   * Returns info-defaults for feed plugin handlers.
   */
  public static function getInfoDefaults() {
    return array(
      'category' => 'fluxfeed',
      'access callback' => array(get_called_class(), 'integrationAccess'),
    );
  }

  /**
   * Rules twitter integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxfeed');
  }

}
