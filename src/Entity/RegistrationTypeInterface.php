<?php

namespace Drupal\event_registration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;

/**
 * Provides an interface for defining Registration Type entities.
 */
interface RegistrationTypeInterface extends ConfigEntityInterface, EntityDescriptionInterface {

  /**
   * Get the supported profile type.
   *
   * @return string
   *   The profile bundle id.
   */
  public function getProfileTypeId();

  /**
   * Set the supported profile type.
   *
   * @param string $profile_type_id
   *   The profile id.
   */
  public function setProfileTypeId($profile_type_id);

  /**
   * Get the supported event bundles.
   *
   * @return string[]
   *   The event bundle ids.
   */
  public function getEventTypeIds();

  /**
   * Set the supported event bundles.
   *
   * @param string[] $event_type_ids
   *   The event bundle ids.
   */
  public function setEventTypeIds(array $event_type_ids);

  /**
   * Add a supported event bundle.
   *
   * @param string $event_type_id
   *   The event bundle id.
   */
  public function addEventTypeId($event_type_id);

  /**
   * Check if a event bundle is supported.
   *
   * @return bool
   *   TRUE if the event type is supported.
   */
  public function supportsEventTypeId($event_type_id);

  /**
   * Get the registerable entity type.
   *
   * @return string
   *   The entity type id.
   */
  public function getTargetEntityTypeId();

  /**
   * Set the registerable entity type.
   *
   * @param string $target_entity_type_id
   *   The entity type id.
   */
  public function setTargetEntityTypeId($target_entity_type_id);

  /**
   * Gets whether the bundle is locked.
   *
   * Locked bundles cannot be deleted.
   *
   * @return bool
   *   TRUE if the bundle is locked, FALSE otherwise.
   */
  public function isLocked();

  /**
   * Locks the bundle.
   *
   * @return $this
   */
  public function lock();

  /**
   * Unlocks the bundle.
   *
   * @return $this
   */
  public function unlock();

}
