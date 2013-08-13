<?php

/**
 * @file
 * Contains theme functions.
 */

/**
 * Preprocess callback for theme_fluxservice_add_account_by_service().
 */
function template_preprocess_fluxservice_add_account_by_service(&$variables) {
  $variables['attributes'] = $variables['element']['#attributes'];
  $variables['item_attributes'] = isset($variables['element']['#item_attributes']) ? $variables['element']['#item_attributes'] : array();
  $variables['links'] = $variables['element']['#links'];

  // Care about the icon.
  $info = fluxservice_get_service_icon_info($variables['element']['#service']);
  $variables['icon'] = theme('fluxservice_icon', $info);
}

/**
 * Theme function for adding an account by service plugin.
 */
function theme_fluxservice_add_account_by_service(&$variables) {
  $links = $variables['links'];
  $output = '';
  if (!empty($links)) {
    $output = '<ul' . drupal_attributes($variables['attributes']) . '>';
    foreach ($links as $item) {
      $output .= '<li>';
      $options = array('html' => TRUE) + (isset($item['options']) ? $item['options'] : array());
      $output .= l($variables['icon'] . $item['title'], $item['href'], $options);
      if (isset($item['description'])) {
        $output .= '<div class="description">' . filter_xss_admin($item['description']) . '</div>';
      }
      $output .= '</li>';
    }
    $output .= '</ul>';
  }
  return $output;
}

/**
 * Preprocess callback for theme_fluxservice_icon().
 */
function template_preprocess_fluxservice_icon(&$variables) {
  // Prepend the right base path.
  if (isset($variables['icon'])) {
    $base_path = isset($variables['icon path']) ? $variables['icon path'] : drupal_get_path('module', $variables['module']);
    $variables['icon'] = $base_path . '/' . $variables['icon'];
  }
  // Put given attributes on the wrapper.
  $variables['wrapper_attributes'] = $variables['attributes'];
  $variables['attributes'] = array();

  $variables['background_color'] = isset($variables['icon background color']) ? $variables['icon background color'] : '#ddd';
  // Add background via inline CSS.
  $variables['wrapper_attributes']['style'][] = "background-color: {$variables['background_color']};";
  $variables['wrapper_attributes']['class'][] = 'fluxservice-icon';

  if (isset($variables['icon'])) {
    $variables['attributes']['class'][] = 'fluxservice-icon-file';
    $variables['icon'] = theme('image', array('path' => $variables['icon']));
  }
  else {
    // Add a default class as fallback for bad modules not providing an icon.
    if (!isset($variables['icon font class'])) {
      $variables['icon font class'] = 'icon-star';
    }
    $variables['attributes']['class'][] = $variables['icon font class'];
    $variables['attributes']['class'][] = 'fluxservice-icon-font';
  }

  // Make sure the CSS is added.
  drupal_add_css(drupal_get_path('module', 'fluxservice') . '/theme/fluxservice.theme.css');
  drupal_add_css('http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css', array('type' => 'external'));
}

/**
 * Theme function for theming a service icon.
 */
function theme_fluxservice_icon(&$variables) {
  $output = '<div' . drupal_attributes($variables['wrapper_attributes']) . '><span' . drupal_attributes($variables['attributes']) . '>';
  $output .= $variables['icon'];
  $output .= '</span>';
  if (isset($variables['children'])) {
    $output .= $variables['children'];
  }
  $output .= '</div>';
  return $output;
}
