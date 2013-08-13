<?php

/**
 * Implements hook_preprocess_panels_pane().
 *
 * Removes all panel and pane classes.
 */
function fluxtheme_preprocess_panels_pane(&$vars) {
  if (isset($vars['classes_array'])) {
    $classes = array();
    foreach ($vars['classes_array'] as $class) {
      if (strpos($class, 'panels') !== 0 && strpos($class, 'pane') !== 0) {
        $classes[] = $class;
      }
    }
    $vars['classes_array'] = $classes;
  }
}
