<?php

/**
 * @file
 * Contains FeedEntry.
 */

namespace Drupal\fluxfeed\Plugin\Entity;

use Drupal\fluxservice\Entity\RemoteEntity;

/**
 * Entity class for feed entries.
 */
class FeedEntry extends RemoteEntity implements FeedEntryInterface {

  /**
   * The decorated feed entry.
   *
   * @var \Zend\Feed\Reader\Feed\FeedInterface
   */
  public $entry;

  /**
   * Defines the entity type.
   *
   * This gets exposed to hook_entity_info() via fluxservice_entity_info().
   */
  public static function getInfo() {
    return array(
      'name' => 'fluxfeed_entry',
      'label' => t('Feed: Entry'),
      'service' => 'fluxfeed',
      'controller class' => '\Drupal\fluxfeed\FeedEntryController',
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
    $info['copyright'] = array(
      'label' => t('Copyright'),
      'description' => t('The copyright entry.'),
      'type' => 'text',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $info['date_created'] = array(
      'label' => t('Created'),
      'description' => t('The timestamp for when the entry was created.'),
      'type' => 'date',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $info['date_modified'] = array(
      'label' => t('Modified'),
      'description' => t('The timestamp for when the entry was last modified.'),
      'type' => 'date',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $info['description'] = array(
      'label' => t('Description'),
      'description' => t("The entry's description."),
      'type' => 'text',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $info['language'] = array(
      'label' => t('Language'),
      'description' => t("The entry's language."),
      'type' => 'text',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $info['title'] = array(
      'label' => t('Title'),
      'description' => t("The entry's title."),
      'type' => 'text',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $info['link'] = array(
      'label' => t('Link'),
      'description' => t("The entry's link."),
      'type' => 'uri',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    $info['content'] = array(
      'label' => t('Content'),
      'description' => t("The entry's content."),
      'type' => 'text',
      'getter callback' => 'fluxservice_entity_property_getter_method',
    );

    return $info;
  }

  /**
   * Constructs a FeedEntry object.
   */
  public function __construct(array $values = array()) {
    if (!isset($values['entry'])) {
      throw new \EntityMalformedException('Missing feed entry.');
    }
    parent::__construct($values, 'fluxfeed_entry');
  }

  /**
   * {@inheritdoc}
   */
  public function getCopyright() {
    return $this->entry->getCopyright();
  }

  /**
   * {@inheritdoc}
   */
  public function getDateCreated() {
    return $this->entry->getDateCreated()->getTimestamp();
  }

  /**
   * {@inheritdoc}
   */
  public function getDateModified() {
    return $this->entry->getDateModified()->getTimestamp();
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->entry->getDescription();
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->entry->getId();
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguage() {
    return $this->entry->getLanguage();
  }

  /**
   * {@inheritdoc}
   */
  public function getLink() {
    return $this->entry->getLink();
  }

  /**
   * {@inheritdoc}
   */
  public function getFeedLink() {
    return $this->entry->getFeedLink();
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->entry->getTitle();
  }

}
