<?php

/**
 * @file
 * Definition of efq_views_handler_filter_field_list.
 */

/**
 * Filter handler which uses list-fields as options.
 *
 * @ingroup views_filter_handlers
 */
class efq_views_handler_filter_field_list extends efq_views_handler_filter_field_in_operator {
  function get_value_options() {
    $field = field_info_field($this->definition['field_name']);
    $this->value_options = list_allowed_values($field);
  }
}
