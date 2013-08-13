<?php

/**
 * @file
 * Contains CronEventDispatcherBase.
 */

namespace Drupal\fluxservice\Rules\EventHandler;

/**
 * Cron event dispatcher base class.
 */
abstract class CronEventHandlerBase extends \RulesEventHandlerBase implements \RulesEventDispatcherInterface {

  /**
   * {@inheritdoc}
   */
  public function startWatching() {
    rules_scheduler_schedule_task(array(
      'date' => REQUEST_TIME,
      'identifier' => "{$this->getEventName()}--{$this->getEventNameSuffix()}",
      'config' => '',
      'data' => $this->getSettings(),
      'handler' => $this->getTaskHandler(),
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function stopWatching() {
    db_delete('rules_scheduler')
      ->condition('identifier', "{$this->getEventName()}--{$this->getEventNameSuffix()}")
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function isWatching() {
    return (booL) db_select('rules_scheduler', 'rs')
      ->fields('rs', array('tid'))
      ->condition('identifier', "{$this->getEventName()}--{$this->getEventNameSuffix()}")
      ->range(0, 1)
      ->execute()
      ->rowCount();
  }

  /**
   * Helper method for retrieving the task handler.
   *
   * @return string
   *   The name of the task handler class.
   */
  abstract protected function getTaskHandler();

}
