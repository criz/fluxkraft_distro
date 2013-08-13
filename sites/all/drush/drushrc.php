<?php

/**
 * List of tables whose *data* is skipped by the 'sql-dump' and 'sql-sync'
 * commands when the "--structure-tables-key=common" option is provided.
 * You may add specific tables to the existing array or add a new element.
 */
$options['structure-tables']['common'] = array(
  'cache',
  'cache_admin_menu',
  'cache_bootstrap',
  'cache_block',
  'cache_field',
  'cache_filter',
  'cache_menu',
  'cache_page',
  'cache_image',
  'cache_menu',
  'cache_metatag',
  'cache_page',
  'cache_path',
  'cache_rules',
  'cache_token',
  'cache_update',
  'cache_views',
  'cache_views_data',
  'ctools_css_cache',
  'ctools_object_cache',
  'history',
  'sessions',
  'watchdog',
);

/**
 * List of tables to be omitted entirely from SQL dumps made by the 'sql-dump'
 * and 'sql-sync' commands when the "--skip-tables-key=common" option is
 * provided on the command line.  This is useful if your database contains
 * non-Drupal tables used by some other application or during a migration for
 * example.  You may add new tables to the existing array or add a new element.
 */
# $options['skip-tables']['common'] = array('migration_data1', 'migration_data2');

/**
 * Global default dump directories on the source and target site.
 */
$options['target-dump-dir'] = '/tmp';
$options['source-dump-dir'] = '/tmp';
