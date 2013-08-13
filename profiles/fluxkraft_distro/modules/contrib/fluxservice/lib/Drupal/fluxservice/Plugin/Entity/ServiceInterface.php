<?php

/**
 * @file
 * Contains ServiceInterface.
 */

namespace Drupal\fluxservice\Plugin\Entity;

use Drupal\fluxservice\Entity\FluxEntityInterface;

/**
 * Interface for web service endpoints.
 *
 * This interface must be implemented by service endpoint plugins. In order to
 * be discovered plugin implementation classes must reside in the "Service"
 * directory below a directory declared via hook_fluxservice_plugin_directory()
 * and implement a static getInfo() method returning an array including the
 * following information:
 *   - name: The machine name of the plugin.
 *   - label: The label of the plugin.
 *   - description (optional): A description of the plugin.
 *   - icon: (optional) The file path of an icon to use, relative to the module
 *     or specified icon path. The icon should be a transparent SVG containing
 *     no colors (only #fff). See https://drupal.org/node/2057965 for
 *     instructions on how to create a suiting icon. Note that either an icon or
 *     an 'icon font class' is required. If both an icon font and icon is given,
 *     the icon is preferred.
 *   - icon path: (optional) The base path for the icon. Defaults to the
 *     providing module's directory.
 *   - icon font class: (optional) An icon font class referring to a suiting
 *     icon. Icon font class names should map to the ones as defined by Font
 *     Awesome, while themes might want to choose to provide another icon font.
 *     See http://fortawesome.github.io/Font-Awesome/cheatsheet/.
 *   - icon background color: (optional) The color used as icon background.
 *     Should have a high contrast to white. Defaults to #ddd.
 *
 * See \Drupal\fluxtwitter\Plugin\Service\TwitterService of the fluxtwitter
 * module for an example.
 */
interface ServiceInterface extends FluxEntityInterface {

}
