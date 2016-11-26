<?php

/**
 * Implements hook_preprocess_html().
 */
function fluxtheme_preprocess_html(&$variables) {
  // Load Font Awesome css.
  drupal_add_css('http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css', array('type' => 'external'));
}
