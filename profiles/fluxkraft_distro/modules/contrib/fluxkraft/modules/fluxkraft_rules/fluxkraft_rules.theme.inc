<?php

/**
 * @file
 * Contains theme functions.
 */

/**
 * Preprocess callback for theme_fluxkraft_rules_category_icon().
 */
function template_preprocess_fluxkraft_rules_category_icon(&$variables) {
  $info = array();
  if (!empty($variables['element']['#category'])) {
    $info = RulesUICategory::getInfo($variables['element']['#category']);
  }

  // Set data attribute as fallback for browser that do not support SVG.
  if (!empty($info['label'])) {
    $variables['attributes']['data-symbol'] = substr($info['label'], 0 ,2);
  }
  elseif (!empty($variables['element']['#group'])) {
    $variables['attributes']['data-symbol'] = substr($variables['element']['#group'], 0 ,2);
  }

  $variables['icon'] = isset($info['icon']) ? theme('image', array('path' => $info['icon'])) : NULL;
  $variables['background_color'] = isset($info['icon background color']) ? $info['icon background color'] : NULL;

  if (isset($variables['icon'])) {
    $variables['attributes']['class'][] = 'fluxkraft-rules-icon-file';
  }
  elseif (isset($info['icon font class'])) {
    $variables['attributes']['class'][] = $info['icon font class'];
    $variables['attributes']['class'][] = 'fluxkraft-rules-icon-font';
  }
  else {
    // Set the default icon.
    $variables['attributes']['class'][] = 'fluxkraft-rules-icon-default';
  }
}

/**
 * Theme function for rules category icons.
 */
function theme_fluxkraft_rules_category_icon(&$variables) {
  return '<span' . drupal_attributes($variables['attributes']) . '>' . $variables['icon'] . '</span>';
}


/**
 * Preprocess callback for theme_fluxkraft_rules_category_info().
 */
function template_preprocess_fluxkraft_rules_category_info(&$variables) {
  $variables['attributes']['class'][] = 'fluxkraft-rules-tooltip';
  $variables['content'] = render($variables['element']['#content']);
  $variables['item_type'] = $variables['element']['#item_type'];

  $variables['label'] = '';
  if (!empty($variables['element']['#category'])) {
    $info = RulesUICategory::getInfo($variables['element']['#category']);
    $variables['label'] = $info['label'];
  }
  elseif (!empty($variables['element']['#group'])) {
    $variables['label'] = $variables['element']['#group'];
  }
}

/**
 * Theme function for rules category info.
 */
function theme_fluxkraft_rules_category_info(&$variables) {
  return '<span' . drupal_attributes($variables['attributes']) . '>' . $variables['content'] . '</span>';
}