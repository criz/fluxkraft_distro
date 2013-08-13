<?php

/**
 * Implements hook_drush_init().
 * 
 * Hack to switch language to English when running drush.
 */
function englishdevel_drush_init() {
  if (function_exists('language_list') && function_exists('module_exists')) {
    global $language;
    $languages = language_list();
    $language = $languages['en'];
  }
}
