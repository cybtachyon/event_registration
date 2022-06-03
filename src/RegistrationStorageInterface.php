<?php

namespace Drupal\event_registration;

use Drupal\commerce_order\Entity\OrderItemInterface;
use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\event_registration\Entity\RegistrationInterface;

/**
 * Defines the storage handler class for Registration entities.
 *
 * This extends the base storage class, adding required special handling for
 * Registration entities.
 *
 * @ingroup event_registration
 */
interface RegistrationStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Registration revision IDs for a specific Registration.
   *
   * @param \Drupal\event_registration\Entity\RegistrationInterface $entity
   *   The Registration entity.
   *
   * @return int[]
   *   Registration revision IDs (in ascending order).
   */
  public function revisionIds(RegistrationInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Registration author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Registration revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\event_registration\Entity\RegistrationInterface $entity
   *   The Registration entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(RegistrationInterface $entity);

  /**
   * Unsets the language for all Registration with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

  /**
   * Loads registrations linked to an order item.
   *
   * @param \Drupal\commerce_order\Entity\OrderItemInterface $order_item
   *   The referenced Order Item.
   *
   * @return \Drupal\event_registration\Entity\RegistrationInterface[]
   *   The related registrations.
   */
  public function getRegistrationsForOrderItem(OrderItemInterface $order_item);

}
