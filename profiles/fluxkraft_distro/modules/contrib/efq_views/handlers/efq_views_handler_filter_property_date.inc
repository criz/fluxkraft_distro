<?php

/**
 * Filter handler for date properties.
 */
class efq_views_handler_filter_property_date extends efq_views_handler_filter_date {
  function op_between($field) {
    $this->query->query->propertyCondition($field, parent::op_between($field), 'BETWEEN');
  }

  function op_simple($field) {
    $this->query->query->propertyCondition($field, parent::op_between($field), $this->operator);
  }
}
