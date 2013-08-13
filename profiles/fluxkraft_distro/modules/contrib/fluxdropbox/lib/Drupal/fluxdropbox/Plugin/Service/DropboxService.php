<?php

/**
 * @file
 * Contains DropboxService.
 */

namespace Drupal\fluxdropbox\Plugin\Service;

use Drupal\fluxservice\Service\OAuthServiceBase;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Service plugin implementation for Dropbox.
 */
class DropboxService extends OAuthServiceBase implements DropboxServiceInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxdropbox',
      'label' => t('Dropbox'),
      'description' => t('Provides Dropbox integration for fluxkraft.'),
      'icon font class' => 'icon-dropbox',
      'icon background color' => '#007ee5'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form = parent::settingsForm($form_state);

    return $form;
  }

}
