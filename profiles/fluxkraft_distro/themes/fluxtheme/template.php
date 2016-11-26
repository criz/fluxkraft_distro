<?php
/**
 * @file
 * Template overrides as well as (pre-)process and alter hooks for the
 * fluxtheme theme.
 */

/**
 * Implements hook_theme().
 */
function fluxtheme_theme() {
  return array(
    'rules_config__fluxkraft_rules_teaser' => array(
      'render element' => 'elements',
      'template' => 'templates/rules-config--fluxkraft-teaser',
    )
  );
}

/**
 * Theme function for rules category icons.
 */
function fluxtheme_fluxkraft_rules_category_icon($variables) {
  return '<div class="fluxkraft-rules-icon fluxkraft-rules-icon__' . drupal_html_class($variables['element']['#group']) . '" rel="tooltip" style="background-color: ' . $variables['background_color'] . '"><span' . drupal_attributes($variables['attributes']) . '>' . $variables['icon'] . '</span></div>';
}

/**
 * Theme function for rules category info.
 */
function fluxtheme_fluxkraft_rules_category_info(&$variables) {
  return '<span' . drupal_attributes($variables['attributes']) . ' ><span class="group">' . $variables['label'] . '</span>' . $variables['content'] . '</span>';
}

/**
 * Implementation of hook_preprocess_maintenance_page():
 * @param $variables
 */
function fluxtheme_preprocess_maintenance_page(&$variables) {
  // Load Font Awesome css.
  drupal_add_css('http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css', array('type' => 'external'));
}