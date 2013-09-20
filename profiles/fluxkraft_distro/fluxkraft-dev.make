; ###############################################################
; fluxkraft development make file
;
; You can run it like that:
; $  cd profiles/fluxkraft_distro
; $  drush make fluxkraft-dev.make --no-core --contrib-destination=.
;
; ###############################################################

core = 7.x
api = 2

; ###############################################################
; Contrib Modules
; ###############################################################

defaults[projects][subdir] = contrib
translations[] = de

; Dependencies =================================================================

projects[composer_manager][version] = 1.0-beta7
projects[ctools][version] = 1.3

projects[entity][type] = module
projects[entity][download][type] = git
projects[entity][download][url] = http://git.drupal.org/project/entity.git
projects[entity][download][branch] = 7.x-1.x

projects[features][version] = 2.0-rc3
projects[libraries][version] = 2.1

projects[rules][type] = module
projects[rules][version] = 2.x-dev
projects[rules][download][type] = git
projects[rules][download][url] = http://git.drupal.org/project/rules.git
projects[rules][download][branch] = 7.x-2.x

projects[uuid][version] = 1.0-alpha5
projects[strongarm][version] = 2.0
projects[xautoload][version] = 3.2
projects[views][version] = 3.7

; Core fluxkraft ===============================================================

projects[fluxkraft][type] = module
projects[fluxkraft][download][type] = git
projects[fluxkraft][download][url] = http://git.drupal.org/project/fluxkraft.git
projects[fluxkraft][download][branch] = 7.x-1.x

projects[fluxservice][type] = module
projects[fluxservice][download][type] = git
projects[fluxservice][download][url] = http://git.drupal.org/project/fluxservice.git
projects[fluxservice][download][branch] = 7.x-1.x

projects[fluxtwitter][type] = module
projects[fluxtwitter][download][type] = git
projects[fluxtwitter][download][url] = http://git.drupal.org/project/fluxtwitter.git
projects[fluxtwitter][download][branch] = 7.x-1.x

projects[fluxfacebook][type] = module
projects[fluxfacebook][download][type] = git
projects[fluxfacebook][download][url] = http://git.drupal.org/project/fluxfacebook.git
projects[fluxfacebook][download][branch] = 7.x-1.x

projects[fluxfeed][type] = module
projects[fluxfeed][download][type] = git
projects[fluxfeed][download][url] = http://git.drupal.org/project/fluxfeed.git
projects[fluxfeed][download][branch] = 7.x-1.x

projects[fluxflickr][type] = module
projects[fluxflickr][download][type] = git
projects[fluxflickr][download][url] = http://git.drupal.org/project/fluxflickr.git
projects[fluxflickr][download][branch] = 7.x-1.x

projects[fluxdropbox][type] = module
projects[fluxdropbox][download][type] = git
projects[fluxdropbox][download][url] = http://git.drupal.org/project/fluxdropbox.git
projects[fluxdropbox][download][branch] = 7.x-1.x

; fluxkraft dev extensions======================================================

projects[fluxlinkedin][type] = module
projects[fluxlinkedin][download][type] = git
projects[fluxlinkedin][download][url] = http://git.drupal.org/project/fluxlinkedin.git
projects[fluxlinkedin][download][branch] = 7.x-1.x

projects[fluxxing][type] = module
projects[fluxxing][download][type] = git
projects[fluxxing][download][url] = http://git.drupal.org/project/fluxxing.git
projects[fluxxing][download][branch] = 7.x-1.x

; Theme and Display ============================================================

projects[omega][type] = theme
projects[omega][download][type] = git
projects[omega][download][url] = http://git.drupal.org/project/omega.git
projects[omega][download][branch] = 7.x-4.x
projects[omega][subdir] = ''

projects[fluxtheme][type] = theme
projects[fluxtheme][download][type] = git
projects[fluxtheme][download][url] = http://git.drupal.org/project/fluxtheme.git
projects[fluxtheme][download][branch] = 7.x-1.x
projects[fluxtheme][subdir] = ''

projects[panels][version] = 3.3
projects[panels_everywhere][version] = 1.0-rc1
projects[hurricane][version] = 1.0-beta1

; Utility ======================================================================

projects[admin_menu][version] = 3.0-rc4
projects[efq_views][version] = 1.x-dev
projects[devel][version] = 1.3
projects[module_filter][version] = 1.8


; Patches ======================================================================
