; ###############################################################
; fluxtheme make file
;
; This will be picked up by drush make automatically.
; You can run it manually like that:
; $  cd sites/all  # or whatever your contrib destination should be, e.g.
;                  # profiles/fluxkraft_distro
; $  drush make themes/fluxtheme/fluxtheme.make --no-core --contrib-destination=.
;
; ###############################################################
core = 7.x
api = 2

defaults[libraries][subdir] = "../themes/fluxtheme/components"


; Theme components =============================================================

libraries[selectivizr][download][type] = "file"
libraries[selectivizr][download][url] = "http://selectivizr.com/downloads/selectivizr-1.0.2.zip"

libraries[html5shiv][download][type] = "file"
libraries[html5shiv][download][url] = "https://github.com/aFarkas/html5shiv/archive/master.zip"
