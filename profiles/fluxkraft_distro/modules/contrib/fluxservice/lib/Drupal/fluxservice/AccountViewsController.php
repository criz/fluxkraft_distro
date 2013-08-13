<?php

/**
 * Contains AccountViewsController.
 */

namespace Drupal\fluxservice;

/**
 * Views controller class for personal service accounts.
 */
class AccountViewsController extends \EntityDefaultViewsController {

  /**
   * Implements hook_views_data().
   */
  public function views_data() {
    $data = parent::views_data();
    $fields = &$data['fluxservice_account'];

    $fields['operations'] = array(
      'title' => t('Operations'),
      'help' => t('Displays a list of account entity operations.'),
      'real field' => 'uuid',
      'field' => array(
        'handler' => 'Drupal\fluxservice\Plugin\Views\Handler\AccountOperations',
      ),
    );

    // Override the 'label' property to use the entity label handler.
    $fields['label']['field'] = array(
      'real field' => 'label',
      'handler' => 'Drupal\fluxservice\Plugin\Views\Handler\EntityLabel',
    );

    // Allow empty values for the 'uid' field.
    $fields['uid']['filter']['allow empty'] = TRUE;

    return $data;
  }
}
