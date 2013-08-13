<?php

/**
 * @file
 * Contains PersonalAccountUIController
 */

namespace Drupal\fluxservice_user;

use Drupal\fluxservice\UI\AccountUIController;

/**
 * A service account UI controller for personal UI.
 */
class PersonalAccountUIController extends AccountUIController {

  /**
   * {@inheritdoc}
   */
  public function hook_menu() {
    $items = parent::hook_menu();
    $path = $this->basePath;
    $endpoint = '%fluxservice_service';

    $items[$path] = array(
      'title' => 'Service accounts',
      'page callback' => 'fluxservice_user_personal_accounts_page',
      'page arguments' => array(1),
      'access callback' => 'fluxservice_user_personal_accounts_access',
      'access arguments' => array(1),
    );

    $items["$path/add"] = array(
        'access callback' => 'fluxservice_user_personal_accounts_access',
        'access arguments' => array(1),
      ) + $items["$path/add"];

    $items["$path/add"]['page arguments'][] = 1;

    foreach (fluxservice_get_account_plugin_info() as $plugin => $info) {
      if (fluxservice_get_service_plugin_info($info['service'])) {
        $items["$path/add/$plugin/$endpoint"] = array(
            'access callback' => 'fluxservice_user_personal_accounts_access',
            'access arguments' => array(1),
          ) + $items["$path/add/$plugin/$endpoint"];

        $items["$path/add/$plugin/$endpoint"]['page arguments'][] = 1;
      }
    }
    return $items;
  }
}
