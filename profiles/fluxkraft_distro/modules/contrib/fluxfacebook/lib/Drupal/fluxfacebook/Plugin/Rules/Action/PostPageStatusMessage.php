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
      'label' => t('Post a status message on a page'),
      'parameter' => array(
        'message' => array(
          'type' => 'text',
          'label' => t('Status message'),
        ),
        'page' => array(
          'type' => 'text',
          'label' => t('Facebook page identifier'),
        ),
        'account' => static::getAccountParameterInfo(),
      ),
    );
  }

  /**
   * Executes the action.
   */
  public function execute($message, $page, FacebookAccountInterface $account) {
    $client = $account->client();
    $command = $client->getCommand('postToFeed', array(
      'message' => $message,
      'id' => $page,
    ));
    $client->execute($command);
  }

}
