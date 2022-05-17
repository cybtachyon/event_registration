<?php

namespace Drupal\event_registration;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class RegistrationTypeStorage extends SqlContentEntityStorage implements RegistrationTypeStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(RegistrationTypeInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {event_registration_type_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {event_registration_type_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(RegistrationTypeInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {event_registration_type_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('event_registration_type_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
