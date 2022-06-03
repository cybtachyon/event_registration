<?php

namespace Drupal\event_registration\Entity;

use Drupal\commerce\Entity\CommerceContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\user\UserInterface;

/**
 * Defines the Registration entity.
 *
 * @ingroup event_registration
 *
 * @ContentEntityType(
 *   id = "event_registration",
 *   label = @Translation("Registration"),
 *   bundle_label = @Translation("Registration Type"),
 *   handlers = {
 *     "storage" = "Drupal\event_registration\RegistrationStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\event_registration\RegistrationListBuilder",
 *     "views_data" = "Drupal\event_registration\Entity\RegistrationViewsData",
 *     "translation" = "Drupal\event_registration\RegistrationTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\event_registration\Form\RegistrationForm",
 *       "add" = "Drupal\event_registration\Form\RegistrationForm",
 *       "edit" = "Drupal\event_registration\Form\RegistrationForm",
 *       "delete" = "Drupal\event_registration\Form\RegistrationDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\event_registration\RegistrationHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\event_registration\RegistrationAccessControlHandler",
 *   },
 *   base_table = "event_registration",
 *   data_table = "event_registration_field_data",
 *   revision_table = "event_registration_revision",
 *   revision_data_table = "event_registration_field_revision",
 *   translatable = TRUE,
 *   permission_granularity = "bundle",
 *   admin_permission = "administer event_registration entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/event/registration/registration/{event_registration}",
 *     "add-page" = "/admin/structure/event/registration/registration/add",
 *     "add-form" = "/admin/structure/event/registration/registration/add/{event_registration_type}",
 *     "edit-form" = "/admin/structure/event/registration/registration/{event_registration}/edit",
 *     "delete-form" = "/admin/structure/event/registration/registration/{event_registration}/delete",
 *     "version-history" = "/admin/structure/event/registration/registration/{event_registration}/revisions",
 *     "revision" = "/admin/structure/event/registration/registration/{event_registration}/revisions/{event_registration_revision}/view",
 *     "revision_revert" = "/admin/structure/event/registration/registration/{event_registration}/revisions/{event_registration_revision}/revert",
 *     "revision_delete" = "/admin/structure/event/registration/registration/{event_registration}/revisions/{event_registration_revision}/delete",
 *     "translation_revert" = "/admin/structure/event/registration/registration/{event_registration}/revisions/{event_registration_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/event/registration/registration",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log_message",
 *   },
 *   bundle_entity_type = "event_registration_type",
 *   field_ui_base_route = "entity.event_registration_type.edit_form"
 * )
 */
class Registration extends CommerceContentEntityBase implements RegistrationInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;
  use RevisionLogEntityTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly,
    // make the event_registration owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the revision metadata fields.
    $fields += static::revisionLogBaseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Registration entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Registration entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Registration is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    $fields['event'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Event'))
      ->setDescription(t('Event for the registration.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'event')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'label',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['profile'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Attendee Information'))
      ->setDescription(t('The Attendee Information.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'profile')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'label',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'inline_entity_form',
        'weight' => 5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['registered_entity'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Registered Entity'))
      ->setDescription(t('The entity registerd for the event.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'label',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public static function bundleFieldDefinitions(EntityTypeInterface $entity_type, $bundle, array $base_field_definitions) {
    /** @var \Drupal\commerce_order\Entity\OrderItemTypeInterface $order_item_type */
    $registration_type = RegistrationType::load($bundle);
    if (!$registration_type) {
      throw new \RuntimeException(sprintf('Could not load the "%s" order item type.', $bundle));
    }
    $target_entity_type_id = $registration_type->getTargetEntityTypeId();

    $fields = [];
    $fields['registered_entity'] = clone $base_field_definitions['registered_entity'];
    if ($target_entity_type_id) {
      $fields['registered_entity']->setSetting('target_type', $target_entity_type_id);
    }
    else {
      // This order item type won't reference a purchasable entity. The field
      // can't be removed here, or converted to a configurable one, so it's
      // hidden instead. https://www.drupal.org/node/2346347#comment-10254087.
      $fields['registered_entity']->setRequired(FALSE);
      $fields['registered_entity']->setDisplayOptions('form', [
        'region' => 'hidden',
      ]);
      $fields['registered_entity']->setDisplayConfigurable('form', FALSE);
      $fields['registered_entity']->setDisplayConfigurable('view', FALSE);
      $fields['registered_entity']->setReadOnly(TRUE);

      // Make the title field visible and required.
      $fields['profile'] = clone $base_field_definitions['profile'];
      $fields['profile']->setRequired(TRUE);
      $fields['profile']->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -1,
      ]);
      $fields['profile']->setDisplayConfigurable('form', TRUE);
      $fields['profile']->setDisplayConfigurable('view', TRUE);
    }

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getEvent() {
    $events = $this->getTranslatedReferencedEntities('event');
    return reset($event);
  }

  /**
   * {@inheritdoc}
   */
  public function getRegistrationTicket() {
    $events = $this->getTranslatedReferencedEntities('registration_type');
    return reset($event);
  }

  /**
   * {@inheritdoc}
   */
  public function getOrderItem() {
    $events = $this->getTranslatedReferencedEntities('order_item');
    return reset($event);
  }

}
