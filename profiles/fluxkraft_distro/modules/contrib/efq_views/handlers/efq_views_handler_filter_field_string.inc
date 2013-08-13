<?php

/**
 * String filter for fields.
 */
class efq_views_handler_filter_field_string extends efq_views_handler_filter_string {
  function option_definition() {
    $options = parent::option_definition();
    $options['delta'] = array('default' => NULL);

    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['delta'] = array(
      '#type' => 'textfield',
      '#title' => t('Delta'),
      '#default_value' => $this->options['delta'],
      '#description' => t('Numeric delta group identifier. If provided, only values attached to the same delta are matched. Leave empty to match all values.'),
    );
  }

  function op_simple($column) {
    $delta = is_numeric($this->options['delta']) ? $this->options['delta'] : NULL;
    $this->query->query->fieldCondition($this->definition['field_name'], $column, $this->value, $this->operator, $delta);
  }
}
