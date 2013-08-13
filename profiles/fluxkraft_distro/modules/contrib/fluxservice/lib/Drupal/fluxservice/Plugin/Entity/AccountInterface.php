<?php

/**
 * @file
 * Contains AccountInterface.
 */

namespace Drupal\fluxservice\Plugin\Entity;

use Drupal\fluxservice\Entity\FluxEntityInterface;

/**
 * Interface for web service accounts.
 *
 * This interface must be implemented by service endpoint plugins. In order to
 * be discovered plugin implementation classes must reside in the "Service"
 * directory below a directory declared via hook_fluxservice_plugin_directory()
 * and implement a static getInfo() method returning an array including the
 * following information:
 *   - name: The machine name of the plugin.
 *   - label: The label of the plugin.
 *   - service: The machine name of the service plugin the accounts are for.
 *   - description (optional): A description of the plugin.
 *
 * See \Drupal\fluxtwitter\Plugin\Service\TwitterAccount of the fluxtwitter
 * module for an example.
 */
interface AccountInterface extends FluxEntityInterface {

  /**
   * Gets the service entity that the account is linked.
   *
   * @return Service
   *   The service entity that this account is linked to.
   */
  public function getService();

  /**
   * Sets the service that the account is linked to.
   *
   * @param Service $service
   *   (Optional) The service entity that the account should be linked to.
   *
   * @return AccountInterface
   *   The called object for chaining.
   */
  public function setService(Service $service);

  /**
   * Gets the user that owns the account.
   *
   * @return \stdClass|null
   *   The user entity of the owner of the account or NULL if it is a site-wide
   *   account.
   */
  public function getOwner();

  /**
   * Sets the owner of the account.
   *
   * @param \stdClass|null $user
   *   (Optional) The new owner of the account or NULL if it should be turned
   *   into a site-wide account.
   *
   * @return AccountInterface
   *   The called object for chaining.
   */
  public function setOwner(\stdClass $user = NULL);

  /**
   * Gets the remote identifier of the account.
   *
   * @return string
   *   The remote identifier.
   */
  public function getRemoteIdentifier();

  /**
   * Sets the remote identifier of the account.
   *
   * @param string $identifier
   *   (Optional) The remote identifier of the account.
   *
   * @return AccountInterface
   *   The called object for chaining.
   */
  public function setRemoteIdentifier($identifier);

  /**
   * Allows plugins to provide custom account creation logic.
   */
  public function prepareAccount();

}
