<?php

/**
 * Numeric filter for fields.
 */
class efq_views_handler_filter_field_numeric extends efq_views_handler_filter_numeric {
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
    $this->query->query->fieldCondition($this->definition['field_name'], $column, $this->value['value'], $this->operator, efq_views_extract_delta($this));
  }

  function op_between($column) {
    $this->query->query->fieldCondition($this->definition['field_name'], $column, $this->value['value'], "BETWEEN", efq_views_extract_delta($this));
  }
}
