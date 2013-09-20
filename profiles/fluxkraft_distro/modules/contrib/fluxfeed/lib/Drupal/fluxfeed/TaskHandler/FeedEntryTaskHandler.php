<?php

/**
 * @file
 * Contains FeedEntryTaskHandler.
 */

namespace Drupal\fluxfeed\TaskHandler;

/**
 * Event dispatcher for when a new feed entry appears.
 */
class FeedEntryTaskHandler extends FeedTaskHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function runTask() {
    // We store the remote identifier of the last Tweet that was processed so
    // that we can benefit from the 'since_id' query argument.
    $store = fluxservice_key_value('fluxfeed.date');
    $latest = $date = $store->get($this->task['identifier']) ?: 0;

    $feed = $this->getFeed();
    if ($entries = $feed->read()) {
      foreach ($entries as $item) {
        if (($created = $item->getDateCreated()->getTimeStamp()) <= $date) {
          // Check if we already know about this item.
          continue;
        }

        if ($created > $latest) {
          // Check if the current entry is newer than our current latest entry.
          $latest = $created;
        }

        $values = array('entry' => $item, 'id' => $item->getId());
        $entity = fluxservice_entify($values, 'fluxfeed_entry', $feed);

        rules_invoke_event($this->getEvent(), $feed, $entity);
      }
    }

    // Store the timestamp of the latest entry.
    $store->set($this->task['identifier'], $latest);
  }

}
