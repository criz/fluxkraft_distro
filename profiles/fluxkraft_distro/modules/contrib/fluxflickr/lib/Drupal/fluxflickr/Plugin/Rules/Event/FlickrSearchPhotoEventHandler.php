<?php

/**
 * @file
 * Contains FlickrSearchPhotosEventHandler.
 */

namespace Drupal\fluxflickr\Plugin\Rules\EventHandler;

/**
 * Event handler for Flickr searches.
 */
class FlickrSearchPhotosEventHandler extends FlickrEventHandlerBase {

  /**
   * Defines the event.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxflickr_search_photo',
      'label' => t('A new photo matches a search term'),
      'variables' => array(
        'account' => static::getServiceVariableInfo(),
        'photo' => static::getPhotoVariableInfo(),
        'search' => array(
          'label' => t('Search term'),
          'type' => 'text',
          'description' => t("A free text search. Photos who's title, description or tags contain the text will be returned. You can exclude results that match a term by prepending it with a - character."),
          'optional' => TRUE,
        ),
        'tags' => array(
          'label' => t('Tags'),
          'type' => 'text',
          'description' => t("A comma-delimited list of tags. Photos with one or more of the tags listed will be returned. You can exclude results that match a term by prepending it with a - character."),
          'optional' => TRUE,
        ),
        'tags_mode' => array(
          'label' => t('Tags mode'),
          'type' => 'text',
          'description' => t("Either 'any' for an OR combination of tags, or 'all' for an AND combination. Defaults to 'any' if not specified."),
          'optional' => TRUE,
        ),
        'min_upload_date' => array(
          'label' => t('Minimum upload date'),
          'type' => 'date',
          'description' => t("Photos with an upload date greater than or equal to this value will be returned. The date can be in the form of a unix timestamp or mysql datetime."),
          'optional' => TRUE,
        )
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTaskHandler() {
    return 'Drupal\fluxflickr\TaskHandler\FlickrSearchPhotosTaskHandler';
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaults() {
    $current_time = date('d/m/Y', time());
    $date = date_parse($current_time);
    return array(
      'search' => '',
      'tags' => '',
      'tags_mode' => 'any',
      'min_upload_date' => $date,
    ) + parent::getDefaults();
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    $settings = $this->getSettings();
    $date = $settings['min_upload_date'];
    $date = $date['day'] . '/' . $date['month'] . '/' . $date['year'];
    return t('A new photo matches the search term: %search and %tags_mode of the tags: %tags and a minimum upload date: %min_upload_date', array(
      '%search' => $settings['search'],
      '%tags' => $settings['tags'],
      '%tags_mode' => $settings['tags_mode'],
      '%min_upload_date' => $date,
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array &$form_state) {
    $form = parent::buildForm($form_state);
    $settings = $this->getSettings();

    $form['search'] = array(
      '#type' => 'textfield',
      '#title' => t('Search term'),
      '#description' => t('The search term to look up photos with.'),
      '#default_value' => $settings['search'],
    );

    $form['tags'] = array(
      '#type' => 'textfield',
      '#title' => t('Tags'),
      '#description' => t('A comma-delimited list of tags. Photos with one or more of the tags listed will be returned. You can exclude results that match a term by prepending it with a - character.'),
      '#default_value' => $settings['tags'],
    );

    $form['tags_mode'] = array(
      '#type' => 'select',
      '#options' => array(
        'any' => t('OR'),
        'all' => t('AND'),
      ),
      '#title' => t('Tags mode'),
      '#description' => t("Either OR combination of tags, AND combination. Defaults to OR if not specified."),
      '#default_value' => $settings['tags_mode'],
    );

    $form['min_upload_date'] = array(
      '#type' => 'date',
      '#title' => t('Minimum upload date'),
      '#description' => t("Photos with an upload date greater than or equal to this value will be returned. The date can be in the form of a unix timestamp or mysql datetime."),
      '#default_value' => $settings['min_upload_date'],
    );

    return $form;
  }

}
