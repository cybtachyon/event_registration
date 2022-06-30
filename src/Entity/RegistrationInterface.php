<?php

namespace Drupal\event_registration\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Registration entities.
 *
 * @ingroup event_registration
 */
interface RegistrationInterface extends
  ContentEntityInterface,
  RevisionLogInterface,
  EntityChangedInterface,
  EntityPublishedInterface,
  EntityOwnerInterface
{

  /**
   * Gets the translations of an entity reference field.
   *
   * @param string $field_name
   *   The entity reference field name.
   *
   * @return \Drupal\Core\Entity\ContentEntityInterface[]
   *   The translated entities.
   */
  public function getTranslatedReferencedEntities($field_name);

  /**
   * Gets the translation of a referenced entity.
   *
   * @param string $field_name
   *   The entity reference field name.
   *
   * @return \Drupal\Core\Entity\ContentEntityInterface|null
   *   The translated entity, or NULL if not found.
   */
  public function getTranslatedReferencedEntity($field_name);

  /**
   * Gets the Registration name.
   *
   * @return string
   *   Name of the Registration.
   */
  public function getName();

  /**
   * Sets the Registration name.
   *
   * @param string $name
   *   The Registration name.
   *
   * @return \Drupal\event_registration\Entity\RegistrationInterface
   *   The called Registration entity.
   */
  public function setName($name);

  /**
   * Gets the Registration creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Registration.
   */
  public function getCreatedTime();

  /**
   * Sets the Registration creation timestamp.
   *
   * @param int $timestamp
   *   The Registration creation timestamp.
   *
   * @return \Drupal\event_registration\Entity\RegistrationInterface
   *   The called Registration entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Registration revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Registration revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\event_registration\Entity\RegistrationInterface
   *   The called Registration entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Registration revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Registration revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\event_registration\Entity\RegistrationInterface
   *   The called Registration entity.
   */
  public function setRevisionUserId($uid);

}
