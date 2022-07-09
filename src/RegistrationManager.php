<?php

namespace Drupal\event_registration;

use Drupal\Core\Session\AccountInterface;
use Drupal\event\Entity\EventInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Implements registration manager service.
 */
class RegistrationManager implements RegistrationManagerInterface {

  /**
   * Drupal's entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Create service instance.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   *   Drupal's entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getEnabledRegistrationTypes(EventInterface $event) {
    $registration_type_storage = $this->entityTypeManager->getStorage('event_registration_type');
    $registration_types = $registration_type_storage->loadMultiple();
    $event_type_id = $event->bundle();

    return array_filter($registration_types, function ($registration_type) use ($event_type_id) {
      return $registration_type->supportsEventTypeId($event_type_id);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableRegistrationTypes(EventInterface $event, AccountInterface $account) {
    $event_registration_types = $this->getEnabledRegistrationTypes($event);

    return array_filter($event_registration_types, function ($registration_type) use ($account, $event) {
      $registrat_type_id = $registration_type->id();
      $event_type_id = $event->id();
      return $account->hasPermission("register $registration_type_id for $event_type_id event")
        || $account->hasPermission("register $registration_type_id for event");
    });
  }

}
