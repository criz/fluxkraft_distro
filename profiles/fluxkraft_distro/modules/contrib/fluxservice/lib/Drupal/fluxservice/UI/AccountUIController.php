<?php

/**
 * @file
 * Contains AccountUIController.
 */

namespace Drupal\fluxservice\UI;
use Drupal\fluxservice\Plugin\Entity\ServiceInterface;

/**
 * Controller for fluxservices account UI.
 */
class AccountUIController {

  /**
   * The array of replacements to apply to the base path.
   *
   * @var array
   */
  protected $replacements = array();

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
   * @return AccountUIController
   */
  public static function factory($base_path = 'admin/config/services/fluxservice/accounts') {
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
   * @param string|null $sub_path
   *   The sub path. Defaults to NULL.
   *
   * @return string
   */
  public function getPath($sub_path = NULL) {
    $base_path = strtr($this->basePath, $this->getBasePathReplacements());
    if (isset($sub_path)) {
      return $base_path . '/' . $sub_path;
    }
    return $base_path;
  }

  /**
   * Returns replacements to apply to the base path.
   */
  protected function getBasePathReplacements() {
    return array_map('arg', $this->replacements);
  }

  /**
   * Sets replacements on the base path.
   *
   * @param string $search
   *   What to replace.
   * @param string $argument_id
   *   The id of the argument to get to replace the value, as passed to arg()
   *   else.
   *
   * @return AccountUIController
   *   The called object for chaining.
   */
  public function setBasePathReplacements($search, $argument_id) {
    $this->replacements[$search] = $argument_id;
    return $this;
  }

  /**
   * Returns menu items based upon the configured base path.
   *
   * @return array
   */
  public function hook_menu() {
    $path = $this->basePath;
    $account = '%fluxservice_account';
    $endpoint = '%fluxservice_service';
    $offset = substr_count($this->basePath, '/');

    $defaults = array(
      'file' => 'fluxservice.pages.inc',
      'file path' => drupal_get_path('module', 'fluxservice'),
    );

    $items["$path/add"] = array(
      'title' => 'Add account',
      'page callback' => 'call_user_func',
      'page arguments' => array(array($this, 'addAccountOverview')),
      'access callback' => 'fluxservice_account_access',
      'access arguments' => array('create'),
      'file' => 'fluxservice.pages.inc',
      'type' => MENU_LOCAL_ACTION,
      // Necessary for the action to appear of no tabs are used else. However,
      // watch out for replacements like %user becoming %.
      'tab_root' => strtr($path, array_fill_keys(array_keys($this->replacements), '%')),
    ) + $defaults;

    foreach (fluxservice_get_account_plugin_info() as $plugin => $info) {
      if (fluxservice_get_service_plugin_info($info['service'])) {
        $items["$path/add/$plugin/$endpoint"] = array(
          'title' => $info['label'],
          'title callback' => 'check_plain',
          'load arguments' => array($offset + 2),
          'description' => $info['description'],
          'page callback' => 'call_user_func',
          'page arguments' => array(array($this, 'addAccount'), $offset + 2, $offset + 3),
          'access callback' => 'fluxservice_account_access',
          'access arguments' => array('create'),
        ) + $defaults;
      }
    }

    $items["$path/manage/$account"] = array(
      'title' => 'View',
      'title callback' => 'entity_label',
      'title arguments' => array('fluxservice_account', $offset + 2),
      'page callback' => 'entity_ui_entity_page_view',
      'page arguments' => array($offset + 2),
      'access callback' => 'entity_access',
      'access arguments' => array('update', 'fluxservice_account', $offset + 2),
      'file path' => drupal_get_path('module', 'entity'),
      'file' => 'includes/entity.ui.inc',
    );

    $items["$path/manage/$account/view"] = array(
      'title' => 'View',
      'type' => MENU_DEFAULT_LOCAL_TASK,
      'weight' => -10,
    );

    $items["$path/manage/$account/edit"] = array(
      'title' => 'Edit',
      'page callback' => 'entity_ui_get_form',
      'page arguments' => array('fluxservice_account', $offset + 2, 'edit', array('controller' => $this)),
      'access callback' => 'entity_access',
      'access arguments' => array('update', 'fluxservice_account', $offset + 2),
      'type' => MENU_LOCAL_TASK,
    ) + $defaults;

    $items["$path/manage/$account/delete"] = array(
      'page callback' => 'drupal_get_form',
      'page arguments' => array('fluxservice_account_delete_confirm', $offset + 2, $this),
      'access callback' => 'entity_access',
      'access arguments' => array('delete', 'fluxservice_account', $offset + 2),
      'type' => MENU_CALLBACK,
    ) + $defaults;

    return $items;
  }

  /**
   * Page callback for creating a new account entity.
   */
  public function addAccountOverview() {
    drupal_set_title(t('Add account'));
    $content = array();

    // Load all endpoints that are accessible by the current user.
    if (($entities = entity_load('fluxservice_service'))) {
      array_filter($entities, function ($entity) {
        return entity_access('use', 'fluxservice_service', $entity);
      });
    }

    // Group all service entities by their plugin name.
    $services = array();
    foreach ($entities as $entity) {
      $plugin = $entity->bundle();
      $identifier = $entity->identifier();
      $services[$plugin][$identifier] = $entity;
    }

    // Iterate over all available account plugins and add the matching service
    // endpoints to them.
    foreach (fluxservice_get_account_plugin_info() as $plugin => $info) {
      $links = array();

      if (!empty($services[$info['service']])) {
        foreach ($services[$info['service']] as $identifier => $service) {
          $links[$identifier] = array(
            'href' => $this->getPath("add/$plugin/$identifier"),
            'title' => $service->label(),
            'description' => t('Add a new %endpoint service account.', array('%endpoint' => $service->label())),
          );
        }
      }
      // Check if the current user is allowed to create service endpoints.
      elseif (entity_access('create', 'fluxservice_service')) {
        // The current user is allowed to create new service endpoints, provide
        // a link to the service endpoint creation page.
        $service_info = fluxservice_get_service_plugin_info($info['service']);

        $links['create_service'] = array(
          'href' => "admin/config/services/fluxservice/endpoints/add/{$info['service']}",
          'title' => t('Configure @plugin service', array('@plugin' => $service_info['label'])),
          'description' => t('You have to configure a @plugin service before you can set up an account.', array('@plugin' => $service_info['label'])),
          'options' => array(
            'attributes' => array('class' => 'fluxservice-add-account-no-service'),
            'query' => drupal_get_destination(),
          ),
        );
      }

      $content[$info['service']] = array(
        '#theme' => 'fluxservice_add_account_by_service',
        '#links' => $links,
        '#attributes' => array('class' => array('fluxservice-add-account-by-service')),
        '#service' => $info['service'],
      );

    }

    // Serve the right piece of output for each case.
    if (empty($content) && user_access('administer site configuration')) {
      $content['empty'] = array(
        '#markup' => '<p>' . t('There are no account plugins available. Go to the <a href="@modules">modules page</a> to enable a account plugin providing module.', array(
          '@modules' => url('admin/modules'),
        )) . '</p>',
      );
    }

    return array(
      '#type' => 'container',
      '#attributes' => array('class' => 'fluxservice-add-account-overview'),
      'content' => $content,
    );
  }

  /**
   * Page callback for creating a new account entity.
   */
  public function addAccount($plugin, ServiceInterface $service, $user = NULL) {
    // Give plugin handlers a chance to automate account creation.
    $account = entity_create('fluxservice_account', array('plugin' => $plugin, 'uuid' => uuid_generate()))
      ->setOwner($user)
      ->setService($service)
      ->prepareAccount();

    return entity_ui_get_form('fluxservice_account', $account, 'add', array('controller' => $this));
  }

}
