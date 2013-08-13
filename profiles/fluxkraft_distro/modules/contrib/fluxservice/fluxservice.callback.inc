<?php

/**
 * @file
 * Contains the OAuth page callback.
 */

use Drupal\fluxservice\Service\OAuthAccountInterface;

/**
 * Menu system page callback for OAuth account creation.
 */
function fluxservice_oauth_callback(OAuthAccountInterface $account) {
  // Invoke the plugin specific callback.
  $account->processOAuthCallback();

  // Determine the proper redirection base path.
  $redirect = "admin/config/services/fluxservice/accounts";
  if (($owner = $account->getOwner()) && $identifier = entity_id('user', $owner)) {
    $redirect = "user/$identifier/service-accounts";
  }

  // Make sure we are not saving the same account twice.
  if (fluxservice_account_connected($account)) {
    // Tell the user that the creation of the account succeeded.
    drupal_set_message(t('The account %name has already been connected.', array(
      '%name' => entity_label('fluxservice_account', $account),
    )), 'error');

    // Redirect to the overview page.
    drupal_goto($redirect);
  }

  // Save the account entity.
  $account->save();

  // Tell the user that the creation of the account succeeded.
  drupal_set_message(t('Successfully linked the account %name to this site.', array(
    '%name' => entity_label('fluxservice_account', $account),
  )));

  // Redirect to the account 'view' page.
  drupal_goto("$redirect/manage/{$account->identifier()}");
}
