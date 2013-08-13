<?php

/**
 * Implements hook_panels_layouts().
 */
function fluxtheme_naked_panels_layouts() {
  $items['naked'] = array(
    'title'    => t('Naked layout'),
    'category' => t('drunomics'),
    'icon'     => 'naked.png',
    'theme'    => 'naked',
    //'admin css' => '../../css/panels/naked/naked.admin.css',
    //'css' => '../../css/panels/naked/naked.css',
    'regions' => array(
      'naked'  => t('Naked region'),
    ),
  );

  return $items;
}

/**
 * Implements hook_preprocess_default().
 */
function template_preprocess_naked(&$variables) {
  $variables['attributes_array']['class'][] = 'l-drunomics-naked-panel';
}