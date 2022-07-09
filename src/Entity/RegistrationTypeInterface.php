<?php

namespace Drupal\event_registration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;

/**
 * Provides an interface for defining Registration Type entities.
 */
interface RegistrationTypeInterface extends ConfigEntityInterface, EntityDescriptionInterface {

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
