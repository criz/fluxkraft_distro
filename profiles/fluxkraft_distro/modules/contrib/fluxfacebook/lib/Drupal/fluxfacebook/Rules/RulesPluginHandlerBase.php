<?php

/**
 * @file
 * Contains RulesPluginHandlerBase.
 */

namespace Drupal\fluxfacebook\Rules;

use Drupal\fluxservice\Rules\FluxRulesPluginHandlerBase;

/**
 * Base class for twitter Rules plugin handler.
 */
abstract class RulesPluginHandlerBase extends FluxRulesPluginHandlerBase {

  /**
   * Returns info-defaults for twitter plugin handlers.
   */
  public static function getInfoDefaults() {
    return array(
      'category' => 'fluxfacebook',
      'access callback' => array(get_called_class(), 'integrationAccess'),
    );
  }

  /**
   * Rules twitter integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxfacebook');
  }

  /**
   * Returns info suiting for twitter service account parameters.
   */
  public static function getAccountParameterInfo() {
    return array(
      'type' => 'fluxservice_account',
      'bundle' => 'fluxfacebook',
      'label' => t('Facebook account'),
      'description' => t('The Facebook account under which this shall be executed.'),
    );
  }
}
