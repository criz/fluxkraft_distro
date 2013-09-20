<?php

/**
 * @file
 * Contains TwitterAddUserToList.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\Action;

use Drupal\fluxtwitter\Plugin\Entity\TwitterList;
use Drupal\fluxtwitter\Plugin\Entity\TwitterListInterface;
use Drupal\fluxtwitter\Plugin\Service\TwitterAccountInterface;
use Drupal\fluxtwitter\Rules\RulesPluginHandlerBase;

/**
 * "Add a user to list" action.
 */
class TwitterAddUserToList extends RulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Defines the action.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxtwitter_list_add_user',
      'label' => t('Add a user to a list'),
      'parameter' => array(
        'account' => static::getServiceParameterInfo(),
        'list' => array(
          'type' => 'integer',
          'label' => t('List'),
          'options list' => array(get_called_class(), 'getListOptions'),
        ),
        'user' => array(
          'type' => 'text',
          'label' => t('User to add'),
        ),
      ),
      'group' => t('Twitter'),
    );
  }

  /**
   * Rules 'options list' callback for retrieving a Twitter list options.
   */
  public static function getListOptions($element, $name = NULL) {
    $options = array();
    if (!empty($element->settings['account']) && $account = entity_load_single('fluxservice_account', $element->settings['account'])) {
      $owner = $account->getRemoteIdentifier();
      $lists = $account->client()->getLists(array('id' => $owner));
      $lists = fluxservice_entify_multiple($lists, 'fluxtwitter_list', $account);
      foreach ($lists as $list) {
        if ($list->getUser()->getRemoteIdentifier() != $owner) {
          // Ignore lists that are owned by someone else.
          continue;
        }

        $identifier = $list->getRemoteIdentifier();
        $name = $list->getName();
        $options[$identifier] = $name;
      }
    }
    return $options;
  }

  /**
   * Executes the action.
   */
  public function execute(TwitterAccountInterface $account, $list, $user) {
    $account->client()->listAddMember(array(
      'screen_name' => $user,
      'list_id' => (int) $list,
    ));
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
      '#limit_validation_errors' => array(array('parameter', 'account'), array('parameter', 'list')),
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
      unset($form['parameter']['list']);
      unset($form['parameter']['user']);
      $form['reload']['#limit_validation_errors'] = array(array('parameter', 'account'));
    }
  }


}
