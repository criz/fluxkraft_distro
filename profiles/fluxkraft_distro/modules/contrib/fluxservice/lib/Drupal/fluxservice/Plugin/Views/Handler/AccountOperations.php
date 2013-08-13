<?php

/**
 * Contains AccountOperations.
 */

namespace Drupal\fluxservice\Plugin\Views\Handler;

/**
 * Field handler for listing entity operations for a service account.
 *
 * @ingroup views_field_handlers
 */
class AccountOperations extends \views_handler_field_entity {

  /**
   * Render callback.
   */
  function render($values) {
    $account = $this->get_value($values);

    $element = array();
    $element['#theme'] = 'links';
    $element['#attributes'] = array('class' => array('inline'));

    // Retrieve the proper path for managing the given account entity.
    $prefix = 'admin/config/services/fluxservice/accounts';
    if (($owner = $account->getOwner()) && $uid = entity_id('user', $owner)) {
      $prefix = "user/$uid/service-accounts";
    }

    $identifier = $account->identifier();
    if (entity_access('update', 'fluxservice_account', $account)) {
      $element['#links']['edit'] = array(
        'href' => "$prefix/manage/$identifier/edit",
        'query' => array('destination' => current_path()),
        'title' => t('edit'),
      );
    }

    if (entity_access('delete', 'fluxservice_account', $account)) {
      $element['#links']['delete'] = array(
        'href' => "$prefix/manage/$identifier/delete",
        'query' => array('destination' => current_path()),
        'title' => t('delete'),
      );
    }

    return drupal_render($element);
  }

}
