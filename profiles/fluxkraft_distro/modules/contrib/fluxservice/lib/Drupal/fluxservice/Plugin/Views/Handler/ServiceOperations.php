<?php

/**
 * Contains ServiceOperations.
 */

namespace Drupal\fluxservice\Plugin\Views\Handler;

/**
 * Field handler for listing entity operations for a service endpoint.
 *
 * @ingroup views_field_handlers
 */
class ServiceOperations extends \views_handler_field_entity {

  /**
   * Render callback.
   */
  function render($values) {
    $service = $this->get_value($values);
    $identifier = $service->identifier();

    $element = array();
    $element['#theme'] = 'links';
    $element['#attributes'] = array('class' => array('inline'));

    if (entity_access('update', 'fluxservice_service', $service)) {
      $element['#links']['edit'] = array(
        'href' => "admin/config/services/fluxservice/endpoints/manage/$identifier/edit",
        'query' => array('destination' => current_path()),
        'title' => t('edit'),
      );
    }

    if (entity_access('delete', 'fluxservice_service', $service)) {
      $element['#links']['delete'] = array(
        'href' => "admin/config/services/fluxservice/endpoints/manage/$identifier/delete",
        'query' => array('destination' => current_path()),
        'title' => t('delete'),
      );
    }

    return drupal_render($element);
  }

}
