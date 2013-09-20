<?php

/**
 * @file
 * Contains FacebookGroupStatusesEventHandler.
 */

namespace Drupal\fluxfacebook\Plugin\Rules\EventHandler;

use Drupal\fluxfacebook\Plugin\Service\FacebookAccountInterface;

/**
 * Event handler for polling for Status updates on a group's timeline.
 */
class FacebookGroupStatusesEventHandler extends FacebookEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxfacebook_group_statuses',
      'label' => t("A new status messages was posted on a group's timeline"),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'message' => static::getStatusMessageVariableInfo(),
        'group' => array(
          'type' => 'text',
          'label' => t('Group'),
          'description' => t('The group whose timeline to watch.'),
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxfacebook\TaskHandler\FacebookStatusesTaskHandler';
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $settings = $this->getSettings();
    return t("A new status messages was posted on a group's timeline (%identifier)", array(
      '%identifier' => $settings['owner'],
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    return array(
      'owner' => '',
    ) + parent::getDefaults();
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $settings = $this->getSettings();
    $form = parent::buildForm($form_state);

    // Check if an account has already been selected.
    $selected = !empty($form_state['values']['account']);

    $form['reload'] = array(
      '#weight' => 20,
      '#type' => 'submit',
      '#name' => 'reload',
      '#value' => !$selected ? t('Continue') : t('Reload form'),
      '#limit_validation_errors' => array(array('event'), array('account')),
      '#submit' => array('rules_form_submit_rebuild'),
      '#ajax' => rules_ui_form_default_ajax('fade'),
      '#attributes' => !$selected ? array('class' => array('rules-hide-js')) : array(),
    );

    // Use ajax and  trigger as the reload button.
    $form['account']['#ajax'] = $form['reload']['#ajax'] + array(
      'event' => 'change',
      'trigger_as' => array('name' => 'reload'),
    );

    $options = $selected ? $this->getGroupOptions(entity_load_single('fluxservice_account', $form_state['values']['account'])) : array();
    $form['owner'] = array(
      '#type' => 'select',
      '#title' => t('Group'),
      '#description' => t('The group whose timeline to watch.'),
      '#options' => $options,
      '#default_value' => $settings['owner'],
      '#required' => TRUE,
      '#access' => $selected,
      '#empty_value' => '',
    );

    return $form;
  }

  /**
   * Gets a list of Facebook groups.
   *
   * @param \Drupal\fluxfacebook\Plugin\Service\FacebookAccountInterface $account
   *   The Facebook account for which to look up groups.
   *
   * @return array
   *   A list of Facebook group names keyed by their id.
   */
  protected function getGroupOptions(FacebookAccountInterface $account) {
    $options = array();
    $response = $account->client()->getGroups(array(
      'id' => $account->getRemoteIdentifier(),
      'limit' => 1000,
    ));
    foreach ($response['data'] as $group) {
      $options[$group['id']] = $group['name'];
    }
    asort($options);
    return $options;
  }

}
