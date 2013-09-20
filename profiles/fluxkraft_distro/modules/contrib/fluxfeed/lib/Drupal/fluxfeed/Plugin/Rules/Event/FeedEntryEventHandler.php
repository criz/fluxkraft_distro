<?php

/**
 * @file
 * Contains FeedEntryEventHandler.
 */

namespace Drupal\fluxfeed\Plugin\Rules\EventHandler;

use Drupal\fluxservice\Rules\EventHandler\CronEventHandlerBase;
use Drupal\fluxfeed\Rules\RulesPluginHandlerBase;

/**
 * Cron-based feed entry event handler.
 */
class FeedEntryEventHandler extends CronEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return RulesPluginHandlerBase::getInfoDefaults() + array(
      'name' => 'fluxfeed_feed_entry',
      'label' => t('A new feed entry appeared'),
      'variables' => array(
        'feed' => array(
          'label' => t('Feed'),
          'type' => 'fluxservice_service',
          'bundle' => 'fluxfeed',
          'description' => t('The feed that is being queried.'),
        ),
        'entry' => array(
          'label' => t('Feed entry'),
          'type' => 'fluxfeed_entry',
          'description' => t('The newly discovered feed entry.'),
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxfeed\TaskHandler\FeedEntryTaskHandler';
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $settings = $this->getSettings();
    if (!empty($settings['feed_url'])) {
      return t('@label on %url', array('%url' => $settings['feed_url'], '@label' => $this->eventInfo['label']));
    }
    return $this->eventInfo['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'feed_url' => '',
      'polling_interval' => 900,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $settings = $this->getSettings();

    $form['feed_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Feed URL'),
      '#description' => t('The full URL of the feed.'),
      '#default_value' => $settings['feed_url'],
      '#required' => TRUE,
    );

    $form['polling_interval'] = array(
      '#type' => 'select',
      '#title' => t('Polling interval'),
      '#description' => t('The time to wait before checking for updates. Note that the effecitive update interval is limited by how often the cron maintenance task runs. Requires a correctly configured <a href="@cron">cron maintenance task</a>.', array('@cron' => url('admin/reports/status'))),
      '#options' => array(0 => t('Every cron run')) + drupal_map_assoc(array(300, 900, 1800, 3600, 10800, 21600, 43200, 86400, 604800), 'format_interval'),
      '#default_value' => $settings['polling_interval'],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getEventNameSuffix() {
    return drupal_hash_base64(serialize($this->getSettings()));
  }

}
