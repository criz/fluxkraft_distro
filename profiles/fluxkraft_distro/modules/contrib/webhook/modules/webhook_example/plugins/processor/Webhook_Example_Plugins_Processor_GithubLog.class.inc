<?php

/**
 * @file
 * Example plugin class for logging github pushes to watchdog
 */

/**
 * Webhook Processor Github Logger class.
 *
 * Records github actions in watchdog log.
 */
class Webhook_Example_Plugins_Processor_GithubLog implements Webhook_Plugins_Processor_Interface {

  /**
   * Configuration form.
   */
  public function config_form() {
    // No configuration needed.
    return array();
  }

  /**
   * Processes data.
   */
  public function process(stdClass $data) {
    $level = WATCHDOG_INFO;
    $message = 'New push to <a href="@repository_url">@repository_name</a>/@ref by @pusher_name (@pusher_email). <a href="@compare">View diff on github</a>.';

    if ($data->forced) {
      $level = WATCHDOG_WARNING;
      $message = 'Forced push to <a href="@repository_url">@repository_name</a>/@ref by @pusher_name (@pusher_email). <a href="@compare">View diff on github</a>.';
    }

    $vars = array(
      '@repository_url' => $data->repository->url,
      '@repository_name' => $data->repository->name,
      '@ref' => $data->ref,
      '@pusher_name' => $data->pusher->name,
      '@pusher_email' => $data->pusher->email,
      '@compare' => $data->compare,
    );

    return watchdog('webhook_github_logger', $message, $vars, $level, $data->compare);
  }
}
