<?php

/**
 * @file
 * Contains ServiceUIController.
 */

namespace Drupal\fluxservice\UI;

/**
 * Controller for fluxservices UI.
 */
class ServiceUIController {

  /**
   * The UI base path.
   *
   * @var string
   */
  protected $basePath;

  /**
   * @param string $base_path
   *   (optional) The base path where to expose the UI.
   *
   * @return ServiceUIController
   */
  public static function factory($base_path = 'admin/config/services/fluxservice/endpoints') {
    $class = get_called_class();
    return new $class($base_path);
  }

  /**
   * Constructs the object.
   */
  public function __construct($base_path) {
    $this->basePath = $base_path;
  }

  /**
   * Gets a path given the sub-path to append to the base path.
   *
   * @return string
   */
  public function getPath($sub_path = NULL) {
    if (isset($sub_path)) {
      return $this->basePath . '/' . $sub_path;
    }
    return $this->basePath;
  }

  /**
   * Returns menu items based upon the configured base path.
   *
   * @return array
   */
  public function hook_menu() {
    $path = $this->basePath;
    $endpoint = '%fluxservice_service';
    $offset = substr_count($this->basePath, '/');

    $defaults = array(
      'file' => 'fluxservice.pages.inc',
      'file path' => drupal_get_path('module', 'fluxservice'),
    );

    $items["$path/add"] = array(
      'title' => 'Add endpoint',
      'page callback' => 'call_user_func',
      'page arguments' => array(array($this, 'addServiceOverview')),
      'access callback' => 'fluxservice_service_access',
      'access arguments' => array('create'),
      'file' => 'fluxservice.pages.inc',
      'type' => MENU_LOCAL_ACTION,
    ) + $defaults;

    foreach (fluxservice_get_service_plugin_info() as $plugin => $info) {
      $items["$path/add/$plugin"] = array(
        'title' => $info['label'],
        'description' => $info['description'],
        'page callback' => 'call_user_func',
        'page arguments' => array(array($this, 'addService'), $plugin),
        'access callback' => 'fluxservice_service_access',
        'access arguments' => array('create'),
      ) + $defaults;
    }

    $items["$path/manage/$endpoint"] = array(
      'title' => 'Edit',
      'page callback' => 'entity_ui_get_form',
      'page arguments' => array('fluxservice_service', $offset + 2, 'edit', array('controller' => $this)),
      'access callback' => 'entity_access',
      'access arguments' => array('update', 'fluxservice_service', $offset + 2),
    ) + $defaults;

    $items["$path/manage/$endpoint/edit"] = array(
      'title' => 'Edit',
      'type' => MENU_DEFAULT_LOCAL_TASK,
    );

    $items["$path/manage/$endpoint/delete"] = array(
      'page callback' => 'drupal_get_form',
      'page arguments' => array('fluxservice_service_delete_confirm', $offset + 2, $this),
      'access callback' => 'entity_access',
      'access arguments' => array('delete', 'fluxservice_service', $offset + 2),
      'type' => MENU_CALLBACK,
    ) + $defaults;

    return $items;
  }

  /**
   * Menu page callback for creating a new service endpoint entity.
   */
  public function addServiceOverview() {
    $item = menu_get_item();
    $item['tab_root'] = $this->getPath('add');
    $content = system_admin_menu_block($item);

    // Bypass the service plugin listing if only one plugin is available.
    if (count($content) == 1) {
      $item = array_shift($content);
      drupal_goto($item['href']);
    }

    if (!$output = theme('admin_block_content', array('content' => $content))) {
      $output = '<p>' . t('There are no service plugins available. Go to the <a href="@modules">modules page</a> to enable a service plugin providing module.', array(
          '@modules' => url('admin/modules'),
        )) . '</p>';
    }

    return $output;
  }

  /**
   * Page callback for creating a new service entity.
   */
  public function addService($plugin) {
    $entity = entity_create('fluxservice_service', array('plugin' => $plugin));
    return entity_ui_get_form('fluxservice_service', $entity, 'add', array('controller' => $this));
  }

}
