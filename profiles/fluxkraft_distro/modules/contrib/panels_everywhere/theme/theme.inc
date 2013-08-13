<?php

/**
 * @file
 * Contains preprocess functions for Panels Everywhere themes.
 */

/**
 * Output the panels everywhere task handler.
 *
 * This is usually a panel, and this is used in place of
 * theme('page') so there will be no page.tpl.php
 */
function theme_panels_everywhere_page($vars) {
  $page = &$vars['page'];
  $task = page_manager_get_task('site_template');

  $content = new stdClass();
  $content->title = drupal_get_title();
  $content->content = isset($page['content']) ? $page['content'] : '';

  $args = array($content);
  $contexts = ctools_context_handler_get_task_contexts($task, '', $args);

  // @todo -- we need a way to insert the page content into the panel
  // if for some reason they left out the page content pane.
  $info = ctools_context_handler_render_handler($task, '', $page['#handler'], $contexts, $args, FALSE);

  // @todo -- is this even necessary?
  $page['#panels_everywhere_site_template'] = array(
    'name' => 'site_template',
    'task' => $task,
    'subtask' => '',
    'contexts' => $contexts,
    'arguments' => $args,
  );

  return is_array($info['content']) ? drupal_render($info['content']) : $info['content'];
}

function template_preprocess_pane_header(&$vars) {
  $vars['site_name'] = (theme_get_setting('toggle_name') ? filter_xss_admin(variable_get('site_name', 'Drupal')) : '');

  $vars['site_slogan'] = (theme_get_setting('toggle_slogan') ? filter_xss_admin(variable_get('site_slogan', '')) : '');
  $vars['front_page'] = url();
  $vars['logo'] = theme_get_setting('logo');
  $vars['search_box'] = (theme_get_setting('toggle_search') ? drupal_get_form('search_theme_form') : '');
}

function template_process_pane_header(&$vars) {
  $vars['title'] = drupal_get_title();
}

function template_preprocess_pane_messages(&$vars) {
  $vars['tabs'] = menu_local_tabs();
  $vars['action_links'] = menu_local_actions();
  $vars['help'] = theme('help');
}

function template_process_pane_messages(&$vars) {
  $vars['messages'] = theme('status_messages');
}

function template_preprocess_pane_navigation(&$vars) {
  $vars['main_menu'] = menu_main_menu();
  $vars['secondary_menu'] = menu_secondary_menu();
}

function template_process_pane_navigation(&$vars) {
  $vars['main_menu'] = theme('links__system_main_menu', array(
    'links' => $vars['main_menu'],
    'attributes' => array('id' => 'main-menu', 'class' => array('links', 'clearfix', 'inline')),
    'heading' => t('Main menu')
  ));
  $vars['secondary_menu'] = theme('links__system_secondary_menu', array(
    'links' => $vars['secondary_menu'],
    'attributes' => array('id' => 'secondary-menu', 'class' => array('links', 'clearfix', 'inline')),
    'heading' => t('Secondary menu')
  ));
  $vars['breadcrumb'] = theme('breadcrumb', array('breadcrumb' => drupal_get_breadcrumb()));
}
