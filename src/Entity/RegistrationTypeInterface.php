<?php

namespace Drupal\event_registration\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Registration type entities.
 *
 * @ingroup event_registration
 */
interface RegistrationTypeInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Registration type name.
   *
   * @return string
   *   Name of the Registration type.
   */
  public function getName();

  /**
   * Sets the Registration type name.
   *
   * @param string $name
   *   The Registration type name.
   *
   * @return \Drupal\event_registration\Entity\RegistrationTypeInterface
   *   The called Registration type entity.
   */
  public function setName($name);

  /**
   * Gets the Registration type creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Registration type.
   */
  public function getCreatedTime();

  /**
   * Sets the Registration type creation timestamp.
   *
   * @param int $timestamp
   *   The Registration type creation timestamp.
   *
   * @return \Drupal\event_registration\Entity\RegistrationTypeInterface
   *   The called Registration type entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Registration type revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Registration type revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\event_registration\Entity\RegistrationTypeInterface
   *   The called Registration type entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Registration type revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Registration type revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\event_registration\Entity\RegistrationTypeInterface
   *   The called Registration type entity.
   */
  public function setRevisionUserId($uid);

}
