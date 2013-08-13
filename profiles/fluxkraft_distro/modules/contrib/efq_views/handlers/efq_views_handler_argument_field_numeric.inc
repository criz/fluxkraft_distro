<?php

/**
 * Numeric argument handler for fields.
 */
class efq_views_handler_argument_field_numeric extends views_handler_argument_numeric {
  function query($group_by = false) {
    if (!empty($this->options['break_phrase'])) {
      views_break_phrase($this->argument, $this);
    }
    else {
      $this->value = array($this->argument);
      $this->operator = 'and';
    }

    if (count($this->value) > 1 && $this->operator == 'or') {
      $operator = empty($this->options['not']) ? 'IN' : 'NOT IN';
      $this->query->query->fieldCondition($this->definition['field_name'], $this->real_field, $this->value, $operator);
    }
    else {
      $operator = empty($this->options['not']) ? '=' : '<>';
      foreach ($this->value as $value) {
        $this->query->query->fieldCondition($this->definition['field_name'], $this->real_field, $value, $operator);
      }
    }
  }
}
