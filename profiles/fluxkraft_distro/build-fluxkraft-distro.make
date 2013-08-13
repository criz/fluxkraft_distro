api = 2
core = 7.x
translations[] = de

; Include the definition for how to build Drupal core directly, including patches:
includes[] = drupal-org-core.make

; Download the install profile and recursively build all its dependencies:
projects[fluxkraft_distro][type] = profile
projects[fluxkraft_distro][download][type] = git
projects[fluxkraft_distro][download][url] = http://git.drupal.org/project/fluxkraft_distro.git
projects[fluxkraft_distro][download][branch] = 7.x-1.x
