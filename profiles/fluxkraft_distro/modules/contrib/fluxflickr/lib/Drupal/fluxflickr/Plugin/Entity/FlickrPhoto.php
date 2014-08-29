<?php

/**
 * @file
 * Contains FlickrPhoto.
 */

namespace Drupal\fluxflickr\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Flickr Photos.
 */
class FlickrPhoto extends RemoteEntity implements FlickrPhotoInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxflickr_photo',
      'label' => t('Flickr: Photo'),
      'module' => 'fluxflickr',
      'service' => 'fluxflickr',
      'controller class' => '\Drupal\fluxflickr\FlickrPhotoController',
      'label callback' => 'entity_class_label',
      'entity keys' => array(
        'id' => 'drupal_entity_id',
        'remote id' => 'id',
      ),
    );
  }

  /**
   * Gets the entity property definitions.
   */
  public static function getEntityPropertyInfo($entity_type, $entity_info) {
    $info['id'] = array(
      'label' => t('Remote identifier'),
      'description' => t('The unique remote identifier of the Photo.'),
      'type' => 'integer',
    );

    $info['owner'] = array(
      'label' => t('Owner ID'),
      'description' => t('The ID of the owner of the photo.'),
      'type' => 'text',
    );

    $info['ownername'] = array(
      'label' => t('Owner ID'),
      'description' => t('The ID of the owner of the photo.'),
      'type' => 'text',
    );

    $info['secret'] = array(
      'label' => t('Secret'),
      'description' => t('Secret.'),
      'type' => 'text',
    );

    $info['server'] = array(
      'label' => t('Server ID'),
      'description' => t('The ID of the server.'),
      'type' => 'text',
    );

    $info['farm'] = array(
      'label' => t('Farm ID'),
      'description' => t('The ID of the farm.'),
      'type' => 'text',
    );

    $info['title'] = array(
      'label' => t('Title'),
      'description' => t('The title of the photo.'),
      'type' => 'text',
    );

    $info['originalformat'] = array(
      'label' => t('Original format'),
      'description' => t("The photo original format of the photo."),
      'type' => 'text',
    );

    $info['dateupload'] = array(
      'label' => t('Upload date timestamp'),
      'description' => t("The unix timestamp of the date this photo was uploaded to flickr."),
      'type' => 'integer',
    );

    $info['media'] = array(
      'label' => t('Media'),
      'description' => t("The media type of the photo."),
      'type' => 'text',
    );

    $sizes = array(
      'original' => 'Original',
      'lage' => 'Large',
      'medium_800' => 'Medium 800',
      'medium_640' => 'Medium 640',
      'medium' => 'Medium',
      'small_320' => 'Small 320',
      'small' => 'Small',
      'thumbnail' => 'Thumbnail',
      'large_square' => 'Large Square',
      'square' => 'Square',
    );

    foreach ($sizes as $key => $size) {
      $info["size_$key"] = array(
        'label' => t($size),
        'description' => t("Information about the photo in $size size"),
        'type' => 'struct',
        'getter callback' => 'fluxflickr_photo_metadata_get_size',
        'property info' => array(
          'url' => array(
            'label' => t('Url'),
            'description' => t("The url of the photo in $size size."),
            'type' => 'text',
          ),
          'source' => array(
            'label' => t('Source url'),
            'description' => t("The source url of the photo in $size size."),
            'type' => 'uri',
          ),
          'width' => array(
            'label' => t('Width'),
            'description' => t("The width of the photo in $size size."),
            'type' => 'text',
          ),
          'height' => array(
            'label' => t('Height'),
            'description' => t("The height of the photo  in $size size."),
            'type' => 'text',
          ),
          'label' => array(
            'label' => t('Label'),
            'description' => t("The label of the photo."),
            'type' => 'text',
          ),
          'media' => array(
            'label' => t('Media'),
            'description' => t("The media type of the photo."),
            'type' => 'text',
          ),
        ),
      );
    }

    return $info;
  }

}
