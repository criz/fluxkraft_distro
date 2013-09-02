
--------------------------------------------------------------------------------
                      fluxkraft distribution
--------------------------------------------------------------------------------

Project homepage: http://drupal.org/project/fluxkraft_distro

Users looking for a full release should look for full release downloads at the
project page.

###

Setup instructions for developers:

 * Place the install profile at it's usual location,

     DRUPAL_ROOT/profiles/fluxkraft_distro

 * CD into the installation profile directory and run drush make:

   cd profiles/fluxkraft_distro
   drush make fluxkraft-dev.make --no-core --contrib-destination=.

   fluxkraft-dev.make makes use of Git checkouts for all fluxkraft modules to
   ease development. If you prefer to build the released distribution use
   drupal-org.make instead of fluxkraft-dev.make.

 * Start the installation process as usual by visiting install.php - see
   Drupal's INSTALL.txt. When the installer complains composer manager's
   dependencies being not installed, run the following drush commands:

    drush cc all
    drush composer-json-rebuild
    drush composer-manager update

 * Afterwards, you can continue with the installation process.

###

Creating a a full fluxkraft build:

 * Checkout the fluxkraft distribution

     git clone --branch 7.x-1.x http://git.drupal.org/project/fluxkraft_distro.git

 * Build a full distribution using drush make

     cd fluxkraft_distro
     drush make build-fluxkraft-distro.make installation_folder

 * Run the installation process as described in the developer instructions above.

 * The installation_folder now includes a full fluxkraft build.
