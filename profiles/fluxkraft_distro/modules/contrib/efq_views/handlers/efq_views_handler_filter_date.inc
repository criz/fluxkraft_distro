<?php

/**
 * Filter handler for date properties.
 */
class efq_views_handler_filter_date extends views_handler_filter_date {
  function value_form(&$form, &$form_state) {
    parent::value_form($form, $form_state);
    unset($form['value']['type']);
    $form['value']['value']['#description'] = t('A date in any machine readable format. CCYY-MM-DD HH:MM:SS is preferred.');
  }

  function query() {
    $info = $this->operators();
    if (!empty($info[$this->operator]['method'])) {
      $this->{$info[$this->operator]['method']}($this->real_field);
    }
  }

  function op_between($field) {
    if ($this->operator == 'between') {
      $a = strtotime($this->value['min']);
      $b = strtotime($this->value['max']);
    }
    else {
      $a = strtotime($this->value['max']);
      $b = strtotime($this->value['min']);
    }
    return array($a, $b);
  }

  function op_simple($field) {
    return strtotime($this->value['value']);
  }

}
