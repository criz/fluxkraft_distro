<?php

/**
 * @file
 * Contains FacebookStatusesTaskHandler.
 */

namespace Drupal\fluxfacebook\TaskHandler;

/**
 * Event dispatcher for the Facebook home timeline of a given user.
 */
class FacebookStatusesTaskHandler extends FacebookTaskHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function runTask() {
    $user = $this->task['data']['user'];

    // Assemble the request arguments.
    $arguments = array(
      'id' => $user,
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
        rules_invoke_event($this->getEvent(), $account, $message, $user);
      }

      // Store the timestamp of the last status message that was processed.
      $last = end($messages);
      $store->set($this->task['identifier'], $last->getUpdatedTime());
    }
  }

}
