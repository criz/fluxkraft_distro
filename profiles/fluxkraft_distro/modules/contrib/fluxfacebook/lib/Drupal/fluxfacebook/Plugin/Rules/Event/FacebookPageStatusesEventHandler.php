<?php

/**
 * @file
 * Contains FacebookPageStatusesEventHandler.
 */

namespace Drupal\fluxfacebook\Plugin\Rules\EventHandler;

use Drupal\fluxfacebook\Plugin\Service\FacebookAccountInterface;

/**
 * Event handler for polling for Status updates on a page's timeline.
 */
class FacebookPageStatusesEventHandler extends FacebookEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxfacebook_page_statuses',
      'label' => t("A new status messages was posted on a page's timeline"),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'message' => static::getStatusMessageVariableInfo(),
        'page' => array(
          'type' => 'text',
          'label' => t('Page'),
          'description' => t('The page whose timeline to watch.'),
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
    return t("A new status messages was posted on a page's timeline (%identifier)", array(
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

    // Use ajax and trigger as the reload button.
    $form['account']['#ajax'] = $form['reload']['#ajax'] + array(
        'event' => 'change',
        'trigger_as' => array('name' => 'reload'),
      );

    $options = $selected ? $this->getPageOptions(entity_load_single('fluxservice_account', $form_state['values']['account'])) : array();
    $form['owner'] = array(
      '#type' => 'select',
      '#title' => t('Page'),
      '#description' => t('The page whose timeline to watch.'),
      '#options' => $options,
      '#default_value' => $settings['owner'],
      '#required' => TRUE,
      '#access' => $selected,
      '#empty_value' => '',
    );

    return $form;
  }

  /**
   * Gets a list of liked Facebook pages.
   *
   * @param \Drupal\fluxfacebook\Plugin\Service\FacebookAccountInterface $account
   *   The Facebook account for which to look up liked pages.
   *
   * @return array
   *   A list of liked Facebook page names keyed by their id.
   */
  protected function getPageOptions(FacebookAccountInterface $account) {
    $options = array();
    $response = $account->client()->getLikes(array(
      'id' => $account->getRemoteIdentifier(),
      'limit' => 1000,
    ));
    foreach ($response['data'] as $page) {
      $options[$page['id']] = "{$page['name']} ({$page['category']})";
    }
    asort($options);
    return $options;
  }

}
