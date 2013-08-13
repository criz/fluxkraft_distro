<?php
// $Id$

/**
 * Sort handler for entity properties.
 */
class efq_views_handler_sort_property extends views_handler_sort {
  function query() {
    $this->query->query->propertyOrderBy($this->real_field, $this->options['order']);
  }
}
