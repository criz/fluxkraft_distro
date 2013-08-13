<?php

/**
 * Contains ServiceViewsController.
 */

namespace Drupal\fluxservice;

/**
 * Views controller class for service endpoints.
 */
class ServiceViewsController extends \EntityDefaultViewsController {

  /**
   * Implements hook_views_data().
   */
  public function views_data() {
    $data = parent::views_data();
    $fields = &$data['fluxservice_service'];

    $fields['operations'] = array(
      'title' => t('Operations'),
      'help' => t('Displays a list of service endpoint entity operations.'),
      'real field' => 'uuid',
      'field' => array(
        'handler' => 'Drupal\fluxservice\Plugin\Views\Handler\ServiceOperations',
      ),
    );

    // Override the 'label' property to use the entity label handler.
    $fields['label']['field'] = array(
      'real field' => 'label',
      'handler' => 'Drupal\fluxservice\Plugin\Views\Handler\EntityLabel',
    );

    return $data;
  }

}
