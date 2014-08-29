<?php

/**
 * @file
 * Contains FacebookStatusesTaskHandler.
 */

namespace Drupal\fluxfacebook\TaskHandler;

/**
 * Event dispatcher for the Facebook status messages.
 */
class FacebookStatusesTaskHandler extends FacebookTaskHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function runTask() {
    $owner = $this->task['data']['owner'];

    // Assemble the request arguments.
    $arguments = array(
      'id' => $owner,
      'limit' => 25,
    );

    // Retrieve the timestamp of the last status update that was processed and
    // continue right there.
    $store = fluxservice_key_value('fluxfacebook.statuses');
    $arguments['since'] = $store->get($this->task['identifier']) ?: $this->task['date'];

    $account = $this->getAccount();
    if (($response = $account->client()->getStatuses($arguments)) && $messages = array_reverse($response['data'])) {
      foreach ($messages as &$message) {
        $message['type'] = 'status';
      }

      $messages = fluxservice_entify_multiple($messages, 'fluxfacebook_object', $account);
      foreach ($messages as $message) {
        rules_invoke_event($this->getEvent(), $account, $message, $owner);
      }

      // Store the timestamp of the last status message that was processed.
      $last = end($messages);
      $store->set($this->task['identifier'], $last->getUpdatedTime());
    }
  }

}
