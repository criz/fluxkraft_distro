<?php

/**
 * @file
 * Contains TwitterUser.
 */

namespace Drupal\fluxtwitter\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for Twitter users.
 */
class TwitterUser extends RemoteEntity implements TwitterUserInterface {

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxtwitter_user',
      'label' => t('Twitter: User'),
      'module' => 'fluxtwitter',
      'service' => 'fluxtwitter',
      'controller class' => '\Drupal\fluxtwitter\TwitterUserController',
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
    $info['contributors_enabled'] = array(
      'label' => t('Contributor mode'),
      'description' => t("Whether the user has an account with 'contributor mode' enabled."),
      'type' => 'boolean',
    );

    $info['created_at'] = array(
      'label' => t('Creation date'),
      'description' => t('The timestamp for when the user account was created.'),
      'type' => 'date',
      'getter callback' => 'entity_property_verbatim_date_get',
    );

    $info['default_profile'] = array(
      'label' => t('Default profile'),
      'description' => t('Whether the user has not altered the theme or background of their user profile.'),
      'type' => 'boolean',
    );

    $info['default_profile_image'] = array(
      'label' => t('Default profile image'),
      'description' => t('Whether the user has not uploaded their own avatar and a default egg avatar is used instead.'),
      'type' => 'boolean',
    );

    $info['description'] = array(
      'label' => t('Description'),
      'description' => t('The user-defined UTF-8 string describing their account.'),
      'type' => 'text',
    );

    $info['entities'] = array(
      'label' => t('Entities'),
      'description' => t('Entities which have been parsed out of the url or description fields defined by the user. Read more about User Entities.'),
      'type' => 'struct',
    );

    $info['favourites_count'] = array(
      'label' => t('Favorites count'),
      'description' => t("The number of tweets this user has favorited in the account's lifetime."),
      'type' => 'integer',
    );

    $info['follow_request_sent'] = array(
      'label' => t('Follow request sent'),
      'description' => t('When true, indicates that the authenticating user has issued a follow request to this protected user account.'),
      'type' => 'boolean',
    );

    $info['followers_count'] = array(
      'label' => t('Follower count'),
      'description' => t('The number of followers this account currently has.'),
      'type' => 'integer',
    );

    $info['friends_count'] = array(
      'label' => t('Friends count'),
      'description' => t('The number of users this account is following.'),
      'type' => 'integer',
    );

    $info['geo_enabled'] = array(
      'label' => t('GEO enabled'),
      'description' => t('When true, indicates that the user has enabled the possibility of geotagging their Tweets.'),
      'type' => 'boolean',
    );

    $info['id'] = array(
      'label' => t('Unique identifier'),
      'description' => t('The integer representation of the unique identifier for this User.'),
      'type' => 'integer',
    );

    $info['is_translator'] = array(
      'label' => t('Translator'),
      'description' => t("When true, indicates that the user is a participant in Twitter's translator community."),
      'type' => 'boolean',
    );

    $info['lang'] = array(
      'label' => t('Language'),
      'description' => t('When present, indicates a BCP 47 language identifier corresponding to the machine-detected language of the Tweet text, or "und" if no language could be detected.'),
      'type' => 'token',
    );

    $info['listed_count'] = array(
      'label' => t('Listed count'),
      'description' => t('The number of public lists that this user is a member of.'),
      'type' => 'integer',
    );

    $info['location'] = array(
      'label' => t('Location'),
      'description' => t("The user-defined location for this account's profile."),
      'type' => 'text',
    );

    $info['name'] = array(
      'label' => t('Name'),
      'description' => t("The name of the user, as they've defined it."),
      'type' => 'text',
    );

    $info['profile_background_color'] = array(
      'label' => t('Profile background color'),
      'description' => t("The hexadecimal color chosen by the user for their background."),
      'type' => 'text',
    );

    $info['profile_background_image_url'] = array(
      'label' => t('Profile background image URL'),
      'description' => t("A HTTP-based URL pointing to the background image the user has uploaded for their profile."),
      'type' => 'uri',
    );

    $info['profile_background_image_url_https'] = array(
      'label' => t('Profile background image URL (secure)'),
      'description' => t("A HTTPS-based URL pointing to the background image the user has uploaded for their profile."),
      'type' => 'uri',
    );

    $info['profile_background_tile'] = array(
      'label' => t('Profile background tile'),
      'description' => t("When true, indicates that the user's profile_background_image_url should be tiled when displayed."),
      'type' => 'boolean',
    );

    $info['profile_banner_url'] = array(
      'label' => t('Profile banner URL'),
      'description' => t("The HTTPS-based URL pointing to the standard web representation of the user's uploaded profile banner."),
      'type' => 'uri',
    );

    $info['profile_image_url'] = array(
      'label' => t('Profile image URL'),
      'description' => t("A HTTP-based URL pointing to the user's avatar image."),
      'type' => 'uri',
    );

    $info['profile_image_url_https'] = array(
      'label' => t('Profile image URL (secure)'),
      'description' => t("A HTTPS-based URL pointing to the user's avatar image."),
      'type' => 'uri',
    );

    $info['profile_link_color'] = array(
      'label' => t('Profile link color'),
      'description' => t('The hexadecimal color the user has chosen to display links with in their Twitter UI.'),
      'type' => 'text',
    );

    $info['profile_sidebar_border_color'] = array(
      'label' => t('Profile sidebar border color'),
      'description' => t('The hexadecimal color the user has chosen to display sidebar borders with in their Twitter UI.'),
      'type' => 'text',
    );

    $info['profile_sidebar_fill_color'] = array(
      'label' => t('Profile sidebar fill color'),
      'description' => t('The hexadecimal color the user has chosen to display sidebar backgrounds with in their Twitter UI.'),
      'type' => 'text',
    );

    $info['profile_text_color'] = array(
      'label' => t('Profile text color'),
      'description' => t('The hexadecimal color the user has chosen to display text with in their Twitter UI.'),
      'type' => 'text',
    );

    $info['profile_use_background_image'] = array(
      'label' => t('Profile uses background image'),
      'description' => t('When true, indicates the user wants their uploaded background image to be used.'),
      'type' => 'boolean',
    );

    $info['protected'] = array(
      'label' => t('Protected'),
      'description' => t('When true, indicates that this user has chosen to protect their Tweets.'),
      'type' => 'boolean',
    );

    $info['screen_name'] = array(
      'label' => t('Screen name'),
      'description' => t('The screen name, handle, or alias that this user identifies themselves with.'),
      'type' => 'text',
    );

    $info['show_all_inline_media'] = array(
      'label' => t('Screen name'),
      'description' => t('Indicates that the user would like to see media inline. Somewhat disused.'),
      'type' => 'boolean',
    );

    $info['status'] = array(
      'label' => t('Status'),
      'description' => t("If possible, the user's most recent tweet or retweet. In some circumstances."),
      'type' => 'text',
    );

    $info['statuses_count'] = array(
      'label' => t('Statuses count'),
      'description' => t('The number of tweets (including retweets) issued by the user.'),
      'type' => 'integer',
    );

    $info['time_zone'] = array(
      'label' => t('Timezone'),
      'description' => t('A string describing the Time Zone this user declares themselves within.'),
      'type' => 'text',
    );

    $info['url'] = array(
      'label' => t('Url'),
      'description' => t('A URL provided by the user in association with their profile.'),
      'type' => 'uri',
    );

    $info['utc_offset'] = array(
      'label' => t('UTC offset'),
      'description' => t('The offset from GMT/UTC in seconds.'),
      'type' => 'integer',
    );

    $info['verified'] = array(
      'label' => t('Verified'),
      'description' => t('When true, indicates that the user has a verified account.'),
      'type' => 'boolean',
    );

    $info['withheld_in_countries'] = array(
      'label' => t('Withheld in countries'),
      'description' => t('When present, indicates a textual representation of the two-letter country codes this user is withheld from.'),
      'type' => 'text',
    );

    $info['withheld_scope'] = array(
      'label' => t('Withheld scope'),
      'description' => t("When present, indicates whether the content being withheld is the 'status' or a 'user.'"),
      'type' => 'text',
    );

    return $info;
  }

}
