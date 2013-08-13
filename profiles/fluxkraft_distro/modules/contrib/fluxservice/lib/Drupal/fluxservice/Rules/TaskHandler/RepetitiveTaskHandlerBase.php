<?php

/**
 * @file
 * Contains RepetitiveTaskHandlerBase.
 */

namespace Drupal\fluxservice\Rules\TaskHandler;

/**
 * Repetitive task handler base class for dispatching events.
 */
class RepetitiveTaskHandlerBase extends \RulesSchedulerDefaultTaskHandler {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $task) {
    $this->task = $task;
    $this->task['data'] = unserialize($this->task['data']);
  }

  /**
   * {@inheritdoc}
   */
  public function afterTaskQueued() {
    // Continuously reschedule the task.
    db_update('rules_scheduler')
      ->condition('tid', $this->task['tid'])
      ->fields(array('date' => REQUEST_TIME))
      ->execute();
  }

}
