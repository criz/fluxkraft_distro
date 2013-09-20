<?php

$view = new view();
$view->name = 'service_accounts';
$view->description = 'Configured service accounts.';
$view->tag = 'default';
$view->base_table = 'fluxservice_account';
$view->human_name = 'Service accounts';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Service accounts';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'perm';
$handler->display->display_options['access']['perm'] = 'administer fluxservices';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['pager']['options']['items_per_page'] = '10';
$handler->display->display_options['style_plugin'] = 'table';
/* Header: Global: Text area */
$handler->display->display_options['header']['area']['id'] = 'area';
$handler->display->display_options['header']['area']['table'] = 'views';
$handler->display->display_options['header']['area']['field'] = 'area';
$handler->display->display_options['header']['area']['empty'] = TRUE;
$handler->display->display_options['header']['area']['content'] = 'Service accounts allow you to connect your user account with a service like Facebook or Twitter.';
$handler->display->display_options['header']['area']['format'] = 'filtered_html';
/* No results behavior: Global: Text area */
$handler->display->display_options['empty']['area']['id'] = 'area';
$handler->display->display_options['empty']['area']['table'] = 'views';
$handler->display->display_options['empty']['area']['field'] = 'area';
$handler->display->display_options['empty']['area']['label'] = 'No results';
$handler->display->display_options['empty']['area']['empty'] = TRUE;
$handler->display->display_options['empty']['area']['content'] = 'No accounts available.';
$handler->display->display_options['empty']['area']['format'] = 'filtered_html';
/* Field: Service account: Machine-readable name */
$handler->display->display_options['fields']['uuid']['id'] = 'uuid';
$handler->display->display_options['fields']['uuid']['table'] = 'fluxservice_account';
$handler->display->display_options['fields']['uuid']['field'] = 'uuid';
$handler->display->display_options['fields']['uuid']['label'] = '';
$handler->display->display_options['fields']['uuid']['exclude'] = TRUE;
$handler->display->display_options['fields']['uuid']['element_label_colon'] = FALSE;
/* Field: Service account: Label */
$handler->display->display_options['fields']['label']['id'] = 'label';
$handler->display->display_options['fields']['label']['table'] = 'fluxservice_account';
$handler->display->display_options['fields']['label']['field'] = 'label';
$handler->display->display_options['fields']['label']['alter']['make_link'] = TRUE;
$handler->display->display_options['fields']['label']['alter']['path'] = 'admin/config/services/fluxservice/accounts/manage/[uuid]';
$handler->display->display_options['fields']['label']['alter']['absolute'] = TRUE;
$handler->display->display_options['fields']['label']['element_label_colon'] = FALSE;
/* Field: Service account: Plugin */
$handler->display->display_options['fields']['plugin']['id'] = 'plugin';
$handler->display->display_options['fields']['plugin']['table'] = 'fluxservice_account';
$handler->display->display_options['fields']['plugin']['field'] = 'plugin';
$handler->display->display_options['fields']['plugin']['element_label_colon'] = FALSE;
/* Field: Service account: Operations */
$handler->display->display_options['fields']['operations']['id'] = 'operations';
$handler->display->display_options['fields']['operations']['table'] = 'fluxservice_account';
$handler->display->display_options['fields']['operations']['field'] = 'operations';
/* Contextual filter: Service account: Uid */
$handler->display->display_options['arguments']['uid']['id'] = 'uid';
$handler->display->display_options['arguments']['uid']['table'] = 'fluxservice_account';
$handler->display->display_options['arguments']['uid']['field'] = 'uid';
$handler->display->display_options['arguments']['uid']['default_action'] = 'access denied';
$handler->display->display_options['arguments']['uid']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['uid']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['uid']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['uid']['summary_options']['items_per_page'] = '25';

/* Display: Sitewide */
$handler = $view->new_display('page', 'Sitewide', 'sitewide');
$handler->display->display_options['defaults']['arguments'] = FALSE;
$handler->display->display_options['defaults']['filter_groups'] = FALSE;
$handler->display->display_options['defaults']['filters'] = FALSE;
/* Filter criterion: Service account: Uid */
$handler->display->display_options['filters']['uid']['id'] = 'uid';
$handler->display->display_options['filters']['uid']['table'] = 'fluxservice_account';
$handler->display->display_options['filters']['uid']['field'] = 'uid';
$handler->display->display_options['filters']['uid']['operator'] = 'empty';
$handler->display->display_options['path'] = 'admin/config/services/fluxservice/accounts';
$handler->display->display_options['menu']['type'] = 'normal';
$handler->display->display_options['menu']['title'] = 'Service accounts';
$handler->display->display_options['menu']['description'] = 'Manage configured service accounts.';
$handler->display->display_options['menu']['weight'] = '0';
$handler->display->display_options['menu']['name'] = 'management';
$handler->display->display_options['menu']['context'] = 0;
$handler->display->display_options['menu']['context_only_inline'] = 0;

/* Display: Personal */
$handler = $view->new_display('attachment', 'Personal', 'personal');
$handler->display->display_options['defaults']['access'] = FALSE;
$handler->display->display_options['access']['type'] = 'none';
$handler->display->display_options['pager']['type'] = 'some';
