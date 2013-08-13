<?php

/**
 * Integer filter for entity keys.
 */
class efq_views_handler_filter_entity_integer extends efq_views_handler_filter_numeric {
  function op_simple($name) {
    $this->query->query->entityCondition($name, (int) $this->value['value'], $this->operator);
  }

  function op_between($name) {
    $this->query->query->entityCondition($name, (int) array($this->value['min'], (int) $this->value['max']), "BETWEEN");
  }
}
