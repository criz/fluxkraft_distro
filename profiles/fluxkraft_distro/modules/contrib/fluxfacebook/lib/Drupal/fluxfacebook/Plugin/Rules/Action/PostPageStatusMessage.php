<?php

/**
 * @file
 * Contains CreatePageStatusMessage.
 */

namespace Drupal\fluxfacebook\Plugin\Rules\Action;

use Drupal\fluxfacebook\Plugin\Service\FacebookAccountInterface;
use Drupal\fluxfacebook\Rules\RulesPluginHandlerBase;

/**
 * Action for posting a status message on a page.
 */
class PostPageStatusMessage extends RulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Defines the action.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxfacebook_create_page_status_message',
      'label' => t('Post a page status message on one of my pages'),
      'parameter' => array(
        'account' => static::getAccountParameterInfo(),
        'page' => array(
          'type' => 'integer',
          'label' => t('Page'),
          'options list' => array(get_called_class(), 'getAccountOptions'),
        ),
        'message' => array(
          'type' => 'text',
          'label' => t('Status message'),
        ),
      ),
    );
  }

  /**
   * Rules 'options list' callback for retrieving account options.
   */
  public static function getAccountOptions($element, $name = NULL) {
    $options = array();
    if (!empty($element->settings['account']) && $account = entity_load_single('fluxservice_account', $element->settings['account'])) {
      $response = $account->client()->getAccounts(array('id' => 'me'));
      foreach ($response['data'] as $page) {
        $options[$page['id']] = "{$page['name']} ({$page['category']})";
      }
    }
    return $options;
  }

  /**
   * Executes the action.
   */
  public function execute(FacebookAccountInterface $account, $page, $message) {
    $client = $account->client();
    $command = $client->getCommand('postToFeed', array(
      'message' => $message,
      'id' => $page,
    ));
    $client->execute($command);
  }

  /**
   * {@inheritdoc}
   */
  public function form_alter(&$form, $form_state, $options) {
    $selected = !empty($this->element->settings['account']);

    $form['reload'] = array(
      '#weight' => $form['submit']['#weight'] + 1,
      '#type' => 'submit',
      '#name' => 'reload',
      '#value' => !$selected ? t('Continue') : t('Reload form'),
      '#limit_validation_errors' => array(array('parameter', 'account'), array('parameter', 'page')),
      '#submit' => array('rules_form_submit_rebuild'),
      '#ajax' => rules_ui_form_default_ajax('fade'),
      '#attributes' => array('class' => array('rules-hide-js')),
    );

    // Use ajax and trigger as the reload button.
    $form['parameter']['account']['settings']['account']['#ajax'] = $form['reload']['#ajax'] + array(
      'event' => 'change',
      'trigger_as' => array('name' => 'reload'),
    );

    if (empty($selected)) {
      unset($form['parameter']['page']);
      unset($form['parameter']['message']);
      $form['reload']['#limit_validation_errors'] = array(array('parameter', 'account'));
    }
  }

}
