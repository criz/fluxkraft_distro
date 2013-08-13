<?php

/**
 * Implements hook_preprocess_html().
 */
function fluxtheme_preprocess_html(&$variables) {
  $path = drupal_get_path('theme', 'fluxtheme');
  // Load fallback css.
  drupal_add_css("$path/css/fluxtheme.fallback.css", array(
    'browsers' => array(
      '!IE' => FALSE,
      'IE' => 'lte IE 8',
    ),
    'group' => CSS_THEME,
    'weight' => 100
  ));
  // Load Font Awesome css.
  drupal_add_css('http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css', array('type' => 'external'));
}
