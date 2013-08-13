<?php

/**
 * @file
 * Contains FlickrService.
 */

namespace Drupal\fluxflickr\Plugin\Service;

use Drupal\fluxservice\Service\OAuthServiceBase;

/**
 * Service plugin implementation for Flickr.
 */
class FlickrService extends OAuthServiceBase implements FlickrServiceInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxflickr',
      'label' => t('Flickr'),
      'description' => t('Provides Flickr integration for fluxkraft.'),
      'icon font class' => 'icon-flickr',
      'icon background color' => '#ff0084'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form = parent::settingsForm($form_state);

    $form['help'] = array(
      '#type' => 'markup',
      '#markup' => t('In the following, you need to provide authentication details for communicating with flickr.<br/>For that, you have to create an application in the <a href="http://www.flickr.com/services/apps/create">flickr App Garden</a> and provide its consumer key and secret below.'),
      '#prefix' => '<p class="fluxservice-help">',
      '#suffix' => '</p>',
      '#weight' => -10,
    );

    $form['rules']['polling_interval'] = array(
      '#type' => 'select',
      '#title' => t('Polling interval'),
      '#default_value' => $this->getPollingInterval(),
      '#options' => array(0 => t('Every cron run')) + drupal_map_assoc(array(300, 900, 1800, 3600, 10800, 21600, 43200, 86400, 604800), 'format_interval'),
      '#description' => t('The time to wait before checking for updates. Note that the effecitive update interval is limited by how often the cron maintenance task runs. Requires a correctly configured <a href="@cron">cron maintenance task</a>.', array('@cron' => url('admin/reports/status'))),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getPollingInterval() {
    return $this->data->get('polling_interval');
  }

  /**
   * {@inheritdoc}
   */
  public function setPollingInterval($interval) {
    $this->data->set('polling_interval', $interval);
    return $this;
  }
}
