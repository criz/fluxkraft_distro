<?php

/**
 * @file
 * Contains TwitterActionBase.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\Action;

use Drupal\fluxservice\Rules\FluxRulesPluginHandlerBase;

/**
 * Base class for twitter Rules plugin handler.
 */
abstract class TwitterActionBase extends FluxRulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Returns info-defaults for twitter plugin handlers.
   */
  public static function getInfoDefaults() {
    return array(
      'category' => 'fluxtwitter',
      'access callback' => array(get_called_class(), 'integrationAccess'),
    );
  }

  /**
   * Rules twitter integration access callback.
   */
  public static function integrationAccess($type, $name) {
    return fluxservice_access_by_plugin('fluxtwitter');
  }

  /**
   * Returns info suiting for twitter service account parameters.
   */
  public static function getServiceParameterInfo() {
    return array(
      'type' => 'fluxservice_account',
      'bundle' => 'fluxtwitter',
      'label' => t('Twitter account'),
      'description' => t('The Twitter account under which this shall be executed.'),
    );
  }

}
