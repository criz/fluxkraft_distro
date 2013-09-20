<?php

$view = new view();
$view->name = 'service_endpoints';
$view->description = 'Configured service endpoints.';
$view->tag = 'default';
$view->base_table = 'fluxservice_service';
$view->human_name = 'Service endpoints';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Service endpoints';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'none';
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
$handler->display->display_options['header']['area']['content'] = 'Service endpoints are required to connect to services like Facebook or Twitter. For each of those services, an endpoint is required in order to create Service accounts on a per-user basis.';
$handler->display->display_options['header']['area']['format'] = 'filtered_html';
/* No results behavior: Global: Text area */
$handler->display->display_options['empty']['area']['id'] = 'area';
$handler->display->display_options['empty']['area']['table'] = 'views';
$handler->display->display_options['empty']['area']['field'] = 'area';
$handler->display->display_options['empty']['area']['label'] = 'No results';
$handler->display->display_options['empty']['area']['empty'] = TRUE;
$handler->display->display_options['empty']['area']['content'] = 'You did not configure any service endpoints yet.';
$handler->display->display_options['empty']['area']['format'] = 'filtered_html';
/* Field: Service endpoint: Label */
$handler->display->display_options['fields']['label']['id'] = 'label';
$handler->display->display_options['fields']['label']['table'] = 'fluxservice_service';
$handler->display->display_options['fields']['label']['field'] = 'label';
$handler->display->display_options['fields']['label']['element_label_colon'] = FALSE;
/* Field: Service endpoint: Machine-readable name */
$handler->display->display_options['fields']['uuid']['id'] = 'uuid';
$handler->display->display_options['fields']['uuid']['table'] = 'fluxservice_service';
$handler->display->display_options['fields']['uuid']['field'] = 'uuid';
/* Field: Service endpoint: Plugin */
$handler->display->display_options['fields']['plugin']['id'] = 'plugin';
$handler->display->display_options['fields']['plugin']['table'] = 'fluxservice_service';
$handler->display->display_options['fields']['plugin']['field'] = 'plugin';
$handler->display->display_options['fields']['plugin']['element_label_colon'] = FALSE;
/* Field: Service endpoint: Status */
$handler->display->display_options['fields']['status']['id'] = 'status';
$handler->display->display_options['fields']['status']['table'] = 'fluxservice_service';
$handler->display->display_options['fields']['status']['field'] = 'status';
$handler->display->display_options['fields']['status']['element_label_colon'] = FALSE;
/* Field: Service endpoint: Operations */
$handler->display->display_options['fields']['operations']['id'] = 'operations';
$handler->display->display_options['fields']['operations']['table'] = 'fluxservice_service';
$handler->display->display_options['fields']['operations']['field'] = 'operations';

/* Display: Page */
$handler = $view->new_display('page', 'Page', 'page');
$handler->display->display_options['path'] = 'admin/config/services/fluxservice/endpoints';
$handler->display->display_options['menu']['type'] = 'normal';
$handler->display->display_options['menu']['title'] = 'Service endpoints';
$handler->display->display_options['menu']['description'] = 'Manage configured service endpoints.';
$handler->display->display_options['menu']['weight'] = '0';
$handler->display->display_options['menu']['name'] = 'management';
$handler->display->display_options['menu']['context'] = 0;
$handler->display->display_options['menu']['context_only_inline'] = 0;
