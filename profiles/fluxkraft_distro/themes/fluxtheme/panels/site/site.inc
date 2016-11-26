<?php

/**
 * Implements hook_panels_layouts().
 */
function fluxtheme_site_panels_layouts() {
  $items['site'] = array(
    'title'    => t('Site Layout'),
    'category' => t('drunomics'),
    'icon'     => 'site.png',
    'theme'    => 'site',
    'admin css' => '../../css/site/site.admin.css',
    'css' => '../../css/site/site.css',
    'regions' => array(
      'header'  => t('Header'),
      'main'  => t('Main'),
      'footer_col1'  => t('Footer Column 1'),
      'footer_col2'  => t('Footer Column 2'),
      'footer_col3'  => t('Footer Column 3'),
      'footer_col4'  => t('Footer Column 4'),
      'bottom'  => t('Bottom'),
    ),
  );
  return $items;
}

/**
 * Implements hook_preprocess_default().
 *
 * Removes the 'site' class.
 */
function template_preprocess_site(&$variables) {
    $path = drupal_get_path('theme', 'fluxtheme');
  drupal_add_css("$path/css/site/site.fallback.css", array(
    'browsers' => array(
      '!IE' => FALSE,
      'IE' => 'lte IE 8',
    ),
    'group' => CSS_THEME,
    'weight' => 100
  ));

  if (isset($variables['attributes_array']['class'])) {
    $i = array_search('site', $variables['attributes_array']['class']);
    if ($i !== FALSE) {
      unset($variables['attributes_array']['class'][$i]);
    }
  }
}
