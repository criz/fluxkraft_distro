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
function fluxtheme_fluxkraft_rules_category_icon(&$variables) {
  return '<div class="fluxkraft-rules-icon fluxkraft-rules-icon__' . drupal_html_class($variables['element']['#group']) . '" rel="tooltip" style="background-color: ' . $variables['background_color'] . '"><span' . drupal_attributes($variables['attributes']) . '>' . $variables['icon'] . '</span></div>';
}

/**
 * Theme function for rules category info.
 */
function fluxtheme_fluxkraft_rules_category_info(&$variables) {
  return '<span' . drupal_attributes($variables['attributes']) . ' ><span class="group">' . $variables['label'] . '</span>' . $variables['content'] . '</span>';
}

function fluxtheme_omega_theme_libraries_info_alter(&$info) {
  $theme_path = drupal_get_path('theme', 'fluxtheme');
  // Set right path to selectivizr files downloaded by make file. Todo: Improve
  if (isset($info['selectivizr']['files']['js'][$theme_path . '/components/selectivizr/selectivizr.min.js'])) {
    $info['selectivizr']['files']['js'][$theme_path . '/components/selectivizr/selectivizr-min.js'] = $info['selectivizr']['files']['js'][$theme_path . '/components/selectivizr/selectivizr.min.js'];
    unset($info['selectivizr']['files']['js'][$theme_path . '/components/selectivizr/selectivizr.min.js']);
  }
  // Set right path to html5shiv files downloaded by make file. Todo: Improve
  if (isset($info['html5shiv']['files']['js'][$theme_path . '/components/html5shiv-dist/html5shiv.js'])) {
    $info['html5shiv']['files']['js'][$theme_path . '/components/html5shiv/dist/html5shiv.js'] = $info['html5shiv']['files']['js'][$theme_path . '/components/html5shiv-dist/html5shiv.js'];
    unset($info['html5shiv']['files']['js'][$theme_path . '/components/html5shiv-dist/html5shiv.js']);
  }
  if (isset($info['html5shiv']['files']['js'][$theme_path . '/components/html5shiv-dist/html5shiv-printshiv.js'])) {
    $info['html5shiv']['files']['js'][$theme_path . '/components/html5shiv/dist/html5shiv-printshiv.js'] = $info['html5shiv']['files']['js'][$theme_path . '/components/html5shiv-dist/html5shiv-printshiv.js'];
    unset($info['html5shiv']['files']['js'][$theme_path . '/components/html5shiv-dist/html5shiv-printshiv.js']);
  }
}