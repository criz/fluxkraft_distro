<?php

/**
 * @file
 * Contains TwitterTweet.
 */

namespace Drupal\fluxtwitter\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Twitter Tweets.
 */
class TwitterTweet extends RemoteEntity implements TwitterTweetInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtwitter_tweet',
      'label' => t('Twitter: Tweet'),
      'module' => 'fluxtwitter',
      'service' => 'fluxtwitter',
      'controller class' => '\Drupal\fluxtwitter\TwitterTweetController',
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
      'description' => t('The unique remote identifier of the Tweet.'),
      'type' => 'integer',
    );

    $info['created_at'] = array(
      'label' => t('Creation date'),
      'description' => t('The timestamp for when the Tweet was created.'),
      'type' => 'date',
      'getter callback' => 'entity_property_verbatim_date_get',
    );

    $info['text'] = array(
      'label' => t('Content'),
      'description' => t('The content of the Tweet.'),
      'type' => 'text',
      'required' => TRUE,
      'setter callback' => 'entity_property_verbatim_set',
    );

    $info['truncated'] = array(
      'label' => t('Truncated'),
      'description' => t('Indicates whether the value of the text parameter was truncated, for example, as a result of a retweet exceeding the 140 character Tweet length.'),
      'type' => 'boolean',
    );

    $info['source'] = array(
      'label' => t('Source'),
      'description' => t('Utility used to post the Tweet, as an HTML-formatted string. Tweets from the Twitter website have a source value of web.'),
      'type' => 'text',
    );

    $info['in_reply_to_status_id'] = array(
      'label' => t('Original Tweet ID'),
      'description' => t("If the represented Tweet is a reply, this field will contain the string representation of the original Tweet's ID."),
      'type' => 'integer',
    );

    $info['in_reply_to_user_id'] = array(
      'label' => t('Original Tweet author ID'),
      'description' => t("If the represented Tweet is a reply, this field will contain the integer representation of the original Tweet's author ID. This will not necessarily always be the user directly mentioned in the Tweet."),
      'type' => 'integer',
    );

    $info['in_reply_to_screen_name'] = array(
      'label' => t('Original Tweet author screen name'),
      'description' => t("If the represented Tweet is a reply, this field will contain the screen name of the original Tweet's author."),
      'type' => 'text',
    );

    $info['user'] = array(
      'label' => t('Twitter user'),
      'description' => t('The Twitter user who posted this Tweet.'),
      'type' => 'fluxtwitter_user',
    );

    $info['coordinates'] = array(
      'label' => t('Coordinates'),
      'description' => t('Represents the geographic location of this Tweet as reported by the user or client application. The inner coordinates array is formatted as geoJSON (longitude first, then latitude).'),
      'type' => 'struct',
    );

    $info['place'] = array(
      'label' => t('Place'),
      'description' => t('When present, indicates that the tweet is associated (but not necessarily originating from) a Place.'),
      'type' => 'struct',
    );

    $info['contributors'] = array(
      'label' => t('Contributors'),
      'description' => t('A collection of brief user objects (usually only one) indicating users who contributed to the authorship of the tweet, on behalf of the official tweet author.'),
      'type' => 'list<struct>',
      'property info' => array(
        'id' => array(
          'label' => t('Contributor ID'),
          'description' => t('The ID of the Twitter user who contributed to the Tweet.'),
          'type' => 'integer',
        ),
        'screen_name' => array(
          'label' => t('Contributor screen name'),
          'description' => t('The screen name of the Twitter user who contributed to the Tweet.'),
          'type' => 'string',
        ),
      ),
    );

    $info['retweet_count'] = array(
      'label' => t('Retweet count'),
      'description' => t('Number of times this Tweet has been retweeted.'),
      'type' => 'integer',
    );

    $info['favorite_count'] = array(
      'label' => t('Favorite count'),
      'description' => t('Indicates approximately how many times this Tweet has been "favorited" by Twitter users.'),
      'type' => 'integer',
    );

    $info['retweeted'] = array(
      'label' => t('Retweeted'),
      'description' => t('Indicates whether this Tweet has been retweeted by the authenticated user.'),
      'type' => 'boolean',
    );

    $info['favorited'] = array(
      'label' => t('Favorited'),
      'description' => t('Indicates whether this Tweet has been favorited by the authenticated user.'),
      'type' => 'boolean',
    );

    $info['lang'] = array(
      'label' => t('Language'),
      'description' => t('When present, indicates a BCP 47 language identifier corresponding to the machine-detected language of the Tweet text, or "und" if no language could be detected.'),
      'type' => 'token',
    );

    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public static function factory(array $values, $entity_type, $entity_info) {
    $entity = parent::factory($values, $entity_type, $entity_info);

    if (!$entity->isNew() && !empty($values['user'])) {
      // Process the attached Twitter user entity.
      fluxservice_entify_bycatch($values['user'], 'fluxtwitter_user', $entity->getAccount());
    }

    return $entity;
  }

}
