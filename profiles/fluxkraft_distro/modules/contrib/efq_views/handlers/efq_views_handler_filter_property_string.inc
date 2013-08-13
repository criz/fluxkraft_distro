<?php

/**
 * Filter handler for textual properties.
 */
class efq_views_handler_filter_property_string extends efq_views_handler_filter_string {
  function op_simple($column) {
    $this->query->query->propertyCondition($column, $this->value, $this->operator);
  }
}
