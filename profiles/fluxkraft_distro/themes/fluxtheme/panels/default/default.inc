<?php

/**
 * Implements hook_panels_layouts().
 */
function fluxtheme_default_panels_layouts() {
  $items['default'] = array(
    'title'    => t('Default'),
    'category' => t('drunomics'),
    'icon'     => 'default.png',
    'theme'    => 'default',
    'admin css' => '../../css/panels/default/default.admin.css',
    'css' => '../../css/panels/default/default.css',
    'regions' => array(
      'header'  => t('Header'),
      'main'  => t('Main'),
      'sidebar-first'  => t('First sidebar'),
      'sidebar-second'  => t('Second sidebar'),
      'footer'  => t('Footer'),
    ),
  );

  return $items;
}

/**
 * Implements hook_preprocess_default().
 */
function template_preprocess_default(&$variables) {
  $path = drupal_get_path('theme', 'fluxtheme');
  drupal_add_css("$path/css/panels/default/default.fallback.css", array(
    'browsers' => array(
      '!IE' => FALSE,
      'IE' => 'lte IE 8',
    ),
    'group' => CSS_THEME,
    'weight' => 100
  ));

  $variables['attributes_array']['class'][] = 'l-drunomics-default-panel';

  if ($variables['content']['sidebar-first'] && $variables['content']['sidebar-second']) {
    $variables['attributes_array']['class'][] = 'with-both-sidebars';
  }
  elseif ($variables['content']['sidebar-first']) {
    $variables['attributes_array']['class'][] = 'with-first-sidebar';
  }
  elseif ($variables['content']['sidebar-second']) {
    $variables['attributes_array']['class'][] = 'with-second-sidebar';
  }
  else {
    $variables['attributes_array']['class'][] = 'without-sidebar';
  }
}