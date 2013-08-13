<?php

/**
 * Filter handler for date fields.
 */
class efq_views_handler_filter_field_date extends efq_views_handler_filter_date {
  function op_between($field) {
    $this->query->query->fieldCondition($this->definition['field_name'], $this->real_field, 'value', parent::op_between($field), 'BETWEEN');
  }

  function op_simple($field) {
    $this->query->query->fieldCondition($this->definition['field_name'], $this->real_field, parent::op_simple($field), $this->operator);
  }
}
