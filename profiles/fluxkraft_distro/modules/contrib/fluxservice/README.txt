
--------------------------------------------------------------------------------
                         flux Services integration
--------------------------------------------------------------------------------

Project homepage: http://drupal.org/project/fluxservice

Installation:

 * Install the module and its module dependencies as usual.
 * Then, required libraries must be added in via composer manager - for that run
  the following drush commands:

    drush composer-json-rebuild
    drush composer-manager update

 * That's it. You may want to install a 3rd party integration module such as
   the flux Twitter API (https://drupal.org/project/fluxtwitter) for the module
   actually doing someting.
   The module's admin UI is exposed at
     admin/config/services/fluxservice/endpoints and
     admin/config/services/fluxservice/accounts
