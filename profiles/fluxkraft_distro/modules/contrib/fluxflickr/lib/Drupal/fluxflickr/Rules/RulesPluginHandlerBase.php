<?php

/**
 * @file
 * Contains RulesPluginHandlerBase.
 */

namespace Drupal\fluxflickr\Rules;

use Drupal\fluxservice\Rules\FluxRulesPluginHandlerBase;

/**
 * Base class for flickr Rules plugin handler.
 */
abstract class RulesPluginHandlerBase extends FluxRulesPluginHandlerBase {

  /**
   * Returns info-defaults for flickr plugin handlers.
   */
  public static function getInfoDefaults() {
    return array(
      'category' => 'fluxflickr',
      'access callback' => array(get_called_class(), 'integrationAccess'),
    );
  }

  /**
   * Rules flickr integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxflickr');
  }

  /**
   * Returns info suiting for flickr service account parameters.
   */
  public static function getAccountParameterInfo() {
    return array(
      'type' => 'fluxservice_account',
      'bundle' => 'fluxflickr',
      'label' => t('Flickr account'),
      'description' => t('The Flickr account under which this shall be executed.'),
    );
  }
}
