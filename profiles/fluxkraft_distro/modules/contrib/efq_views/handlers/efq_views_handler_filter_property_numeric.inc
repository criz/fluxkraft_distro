<?php

/**
 * Filter handler for numeric properties.
 */
class efq_views_handler_filter_property_numeric extends efq_views_handler_filter_numeric {
  function op_simple($column) {
    $this->query->query->propertyCondition($column, $this->value['value'], $this->operator);
  }

  function op_between($column) {
    $this->query->query->propertyCondition($column, array($this->value['min'], $this->value['max']), "BETWEEN");
  }
}
