<?php

/**
 * Integer argument handler for entity keys.
 */
class efq_views_handler_argument_entity_integer extends views_handler_argument_numeric {
  function query() {
    if (!empty($this->options['break_phrase'])) {
      views_break_phrase($this->argument, $this);
    }
    else {
      $this->value = array($this->argument);
      $this->operator = 'and';
    }

    if (count($this->value) > 1 && $this->operator == 'or') {
      $operator = empty($this->options['not']) ? 'IN' : 'NOT IN';
      $this->query->query->entityCondition($this->real_field, array_map('intval', $this->value), $operator);
    }
    else {
      $operator = empty($this->options['not']) ? '=' : '<>';
      foreach ($this->value as $value) {
        $this->query->query->entityCondition($this->real_field, (int) $value, $operator);
      }
    }
  }
}
