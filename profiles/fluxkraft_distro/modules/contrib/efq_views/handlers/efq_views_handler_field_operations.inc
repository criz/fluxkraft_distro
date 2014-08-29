<?php

// This class must extend from VBO, but it's possible VBO is not installed.
if (class_exists('views_bulk_operations_handler_field_operations')) {
  /**
   * Views Bulk Operations handler field.
   */
  class efq_views_handler_field_operations extends views_bulk_operations_handler_field_operations {

    /**
     * {@inheritdoc}
     *
     * Checks if the base table referenced is EFQ-enabled. If so, returns the
     * expected entity type.
     */
    public function get_entity_type() {
      if (substr($this->view->base_table, 0, 4) === 'efq_') {
        return substr($this->view->base_table, 4);
      }
      else {
        return parent::get_entity_type();
      }
    }

    /**
     * {@inheritdoc}
     *
     * Attempts to extract and return the entity ID for the given row.
     */
    public function get_value($row, $field = NULL) {
      $entity_type = $this->get_entity_type();
      $ids = entity_extract_ids($entity_type, $row);

      if (isset($ids[0]) && !empty($ids[0])) {
        return $ids[0];
      }
      else {
        return parent::get_value($row, $field);
      }
    }

  }
}
