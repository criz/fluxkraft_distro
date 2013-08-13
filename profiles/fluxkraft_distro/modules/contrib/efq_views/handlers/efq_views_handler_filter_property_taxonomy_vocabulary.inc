<?php

class efq_views_handler_filter_property_taxonomy_vocabulary extends efq_views_handler_filter_property_in_operator {

  function get_value_options() {
    $vocabularies = taxonomy_get_vocabularies();
    $options = array();
    foreach ($vocabularies as $vocabulary) {
      $options[$vocabulary->vid] = check_plain($vocabulary->name);
    }
    $this->value_options = $options;
    return $options;
  }

}
