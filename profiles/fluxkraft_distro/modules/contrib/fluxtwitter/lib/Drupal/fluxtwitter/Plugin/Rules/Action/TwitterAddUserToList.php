<?php

/**
 * @file
 * Contains TwitterAddUserToList.
 */

namespace Drupal\fluxtwitter\Plugin\Rules\Action;

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
        'list' => array(
          'type' => 'text',
          'label' => t('List name'),
        ),
        'user' => array(
          'type' => 'text',
          'label' => t('User to add'),
        ),
        'account' => static::getServiceParameterInfo(),
      ),
      'group' => t('Twitter'),
    );
  }

  /**
   * Executes the action.
   */
  public function execute($list, $user, TwitterAccountInterface $account) {
    $account->client()->listAddMember(array(
      'screen_name' => $user,
      'slug' => $list,
      'owner_screen_name' => $account->getRemoteIdentifier(),
    ));
  }

}
