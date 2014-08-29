; Drupal.org release file.
core = 7.x
api = 2

; ###############################################################
; Contrib Modules
; ###############################################################

defaults[projects][subdir] = contrib

; Dependencies =================================================================

projects[composer_manager][version] = 1.6
projects[ctools][version] = 1.4
projects[entity][version] = 1.5
projects[features][version] = 2.2
projects[libraries][version] = 2.2
projects[rules][version] = 2.6
projects[uuid][version] = 1.0-alpha5
projects[strongarm][version] = 2.0
projects[xautoload][version] = 3.2
projects[views][version] = 3.8

; Core fluxkraft ===============================================================

projects[fluxkraft][version] = 1.x-dev
projects[fluxservice][version] = 1.x-dev

projects[fluxtwitter][version] = 1.x-dev
projects[fluxfacebook][version] = 1.x-dev
projects[fluxfeed][version] = 1.x-dev
projects[fluxflickr][version] = 1.x-dev
projects[fluxdropbox][version] = 1.x-dev

; fluxkraft dev extensions======================================================

projects[fluxlinkedin][version] = 1.x-dev
projects[fluxxing][version] = 1.x-dev

; Theme and Display ============================================================

projects[fluxtheme][version] = 1.x-dev
projects[fluxtheme][subdir] = ''
projects[omega][version] = 4.0-rc1
projects[omega][subdir] = ''
projects[panels][version] = 3.3
projects[panels_everywhere][version] = 1.0-rc1
projects[hurricane][version] = 1.x-dev

; Utility ======================================================================

projects[admin_menu][version] = 3.0-rc4
projects[efq_views][version] = 1.x-dev
projects[devel][version] = 1.3
projects[module_filter][version] = 1.8


; Patches ======================================================================
