<?php

/**
 * Contains EntityLabel.
 */

namespace Drupal\fluxservice\Plugin\Views\Handler;

/**
 * Field handler for properly rendering the label of an entity.
 *
 * @ingroup views_field_handlers
 */
class EntityLabel extends \views_handler_field_entity {

  /**
   * Render callback.
   */
  function render($values) {
    if ($entity = $this->get_value($values)) {
      return $entity->label();
    }
  }

}
