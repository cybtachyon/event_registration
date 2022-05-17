<?php

namespace Drupal\event_registration;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\event_registration\Entity\RegistrationTypeInterface;

/**
 * Defines the storage handler class for Registration type entities.
 *
 * This extends the base storage class, adding required special handling for
 * Registration type entities.
 *
 * @ingroup event_registration
 */
interface RegistrationTypeStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Registration type revision IDs for a specific Registration type.
   *
   * @param \Drupal\event_registration\Entity\RegistrationTypeInterface $entity
   *   The Registration type entity.
   *
   * @return int[]
   *   Registration type revision IDs (in ascending order).
   */
  public function revisionIds(RegistrationTypeInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Registration type author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Registration type revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\event_registration\Entity\RegistrationTypeInterface $entity
   *   The Registration type entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(RegistrationTypeInterface $entity);

  /**
   * Unsets the language for all Registration type with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
