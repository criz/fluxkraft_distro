<?php

/**
 * @file
 * Contains PostStatusMessage.
 */

namespace Drupal\fluxfacebook\Plugin\Rules\Action;

use Drupal\fluxfacebook\Plugin\Service\FacebookAccountInterface;
use Drupal\fluxfacebook\Rules\RulesPluginHandlerBase;

/**
 * Action for posting a status message on a user's timeline.
 */
class PostStatusMessage extends RulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Defines the action.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxfacebook_create_status_message',
      'label' => t("Post a status message on a user's timeline."),
      'parameter' => array(
        'account' => static::getAccountParameterInfo(),
        'message' => array(
          'type' => 'text',
          'label' => t('Status message'),
        ),
        'user' => array(
          'type' => 'text',
          'label' => t('User'),
        ),
      ),
    );
  }

  /**
   * Executes the action.
   */
  public function execute($message, $user, FacebookAccountInterface $account) {
    $client = $account->client();
    $command = $client->getCommand('postToFeed', array(
      'message' => $message,
      'id' => $user,
    ));
    $client->execute($command);
  }

}
