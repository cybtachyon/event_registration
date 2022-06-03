<?php

namespace Drupal\event_registration;

use Drupal\commerce_order\Entity\OrderItemInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class RegistrationStorage extends SqlContentEntityStorage implements RegistrationStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(RegistrationInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {event_registration_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {event_registration_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(RegistrationInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {event_registration_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('event_registration_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function getRegistrationsForOrderItem(OrderItemInterface $order_item) {
    return $this->getQuery()
      ->condition('order_item', $order_item->id())
      ->execute();
  }

}
