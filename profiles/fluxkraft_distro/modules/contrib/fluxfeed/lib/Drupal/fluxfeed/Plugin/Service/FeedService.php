<?php

/**
 * @file
 * Contains FeedService.
 */

namespace Drupal\fluxfeed\Plugin\Service;

use Drupal\fluxservice\Plugin\Entity\Service;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Service\Builder\ServiceBuilder;
use Zend\Feed\Exception\ExceptionInterface;
use Zend\Feed\Reader\Reader;

/**
 * Service plugin implementation for Feed.
 */
class FeedService extends Service implements FeedServiceInterface {

  /**
   * Defines the plugin.
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxfeed',
      'label' => t('Feed'),
      'description' => t('Provides integration with RSS and Atomic feeds.'),
      'class' => '\Drupal\fluxfeed\Plugin\Service\FeedService',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function read() {
    $url = $this->getFeedUrl();
    $response = $this->fetch($url);

    try {
      $channel = Reader::importString($response->getBody(TRUE));
      return $channel;
    }
    catch (ExceptionInterface $e) {
      watchdog_exception('fluxfeed', $e);
      drupal_set_message(t('The feed from %url seems to be broken because of error "%error".', array('%url' => $url, '%error' => $e->getMessage())), 'error');
    }
  }

  /**
   * Fetches the configured feed.
   *
   * @param string $url
   *   The URL from which to fetch.
   *
   * @return \Guzzle\Http\Message\Response
   *   The HTTP response object.
   */
  protected function fetch($url) {
    $request = new Client($url);

    try {
      $response = $request->get()->send();
      // Update the feed URL in case of a 301 redirect.
      if ($previous_response = $response->getPreviousResponse()) {
        if ($previous_response->getStatusCode() == 301 && $location = $previous_response->getLocation()) {
          $this->setFeedUrl($location)->save();
        }
      }
      return $response;
    }
    catch (BadResponseException $e) {
      $response = $e->getResponse();
      watchdog('fluxfeed', 'The feed from %url seems to be broken because of error "%error".', array('%url' => $url, '%error' => $response->getStatusCode() . ' ' . $response->getReasonPhrase()), WATCHDOG_WARNING);
      drupal_set_message(t('The feed from %url seems to be broken because of error "%error".', array('%url' => $url, '%error' => $response->getStatusCode() . ' ' . $response->getReasonPhrase())));
    }
    catch (RequestException $e) {
      watchdog('fluxfeed', 'The feed from %url seems to be broken because of error "%error".', array('%url' => $url, '%error' => $e->getMessage()), WATCHDOG_WARNING);
      drupal_set_message(t('The feed from %url seems to be broken because of error "%error".', array('%url' => $url, '%error' => $e->getMessage())));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultSettings() {
    return array(
      'feed_url' => '',
      'polling_interval' => 900,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array &$form_state) {
    $form['feed_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Feed'),
      '#description' => t('The URL of the feed.'),
      '#default_value' => $this->getFeedUrl(),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFeedUrl() {
    return $this->data->get('feed_url');
  }

  /**
   * {@inheritdoc}
   */
  public function setFeedUrl($url) {
    $this->data->set('feed_url', $url);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPollingInterval() {
    return $this->data->get('polling_interval');
  }

  /**
   * {@inheritdoc}
   */
  public function setPollingInterval($interval) {
    $this->data->set('polling_interval', $interval);
    return $this;
  }

}
