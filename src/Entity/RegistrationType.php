<?php

namespace Drupal\event_registration\Entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Registration type entity.
 *
 * @ConfigEntityType(
 *   id = "event_registration_type",
 *   label = @Translation("Registration type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\event_registration\RegistrationTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\event_registration\Form\RegistrationTypeForm",
 *       "edit" = "Drupal\event_registration\Form\RegistrationTypeForm",
 *       "delete" = "Drupal\event_registration\Form\RegistrationTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\event_registration\RegistrationTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "event_registration_type",
 *   admin_permission = "manager event_registration_type entities",
 *   bundle_of = "event_registration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "targetEntityType",
 *     "profileType",
 *     "eventTypes",
 *     "locked",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/event/registration/registration/bundle/{event_registration_type}",
 *     "add-form" = "/admin/structure/event/registration/registration/bundle/add",
 *     "edit-form" = "/admin/structure/event/registration/registration/bundle/{event_registration_type}/edit",
 *     "delete-form" = "/admin/structure/event/registration/registration/bundle/{event_registration_type}/delete",
 *     "register" = "/event/{event}/registration/{event_registration_type}/register",
 *     "collection" = "/admin/structure/event/registration/registration/bundle"
 *   }
 * )
 */
class RegistrationType extends ConfigEntityBundleBase implements RegistrationTypeInterface {

  /**
   * The Registration type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Registration type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The product type description.
   *
   * @var string
   */
  protected $description = '';

  /**
   * The supported event type IDs.
   *
   * @var array
   */
  protected $eventTypes = [];

  /**
   * The profile type ID.
   *
   * @var string
   */
  protected $profileType;

  /**
   * The registerable entity type ID.
   *
   * @var string
   */
  protected $targetEntityType = NULL;

  /**
   * Whether the bundle is locked, indicating that it cannot be deleted.
   *
   * @var bool
   */
  protected $locked = FALSE;

  /**
   * {@inheritdoc}
   */
  public function getDescription() : string {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) : ProductTypeInterface {
    $this->description = $description;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProfileTypeId() {
    return $this->profileType;
  }

  /**
   * {@inheritdoc}
   */
  public function setProfileTypeId($profile_type_id) {
    $this->profileType = $profile_type_id;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEventTypeIds() {
    return $this->eventTypes;
  }

  /**
   * {@inheritdoc}
   */
  public function setEventTypeIds($event_type_ids) {
    $this->eventTypes = $event_type_ids;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addEventTypeId($event_type_id) {
    if (!in_array($event_type_id, $this->eventTypes)) {
      $this->eventTypes[] = $event_type_id;
    }
    return $this;
  }
  
  public function supportsEventTypeId($event_type_id) {
    return in_array($event_type_id, $this->eventTypes);
  }

  /**
   * {@inheritdoc}
   */
  public function getTargetEntityTypeId() {
    return $this->targetEntityType;
  }

  /**
   * {@inheritdoc}
   */
  public function setTargetEntityTypeId($target_entity_type_id) {
    $this->targetEntityType = $target_entity_type_id;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    return (bool) $this->locked;
  }

  /**
   * {@inheritdoc}
   */
  public function lock() {
    $this->locked = TRUE;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function unlock() {
    $this->locked = FALSE;
    return $this;
  }

}
