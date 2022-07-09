<?php

namespace Drupal\event_registration;

use Drupal\Core\Session\AccountInterface;
use Drupal\event\Entity\EventInterface;

/**
 * Defines the api for the registration manager service.
 */
interface RegistrationManagerInterface {

  /**
   * Get enabled registration types for an event.
   *
   * @param \Drupal\event\Entity\EventInterface $event
   *   The event.
   *
   * @return \Drupal\event_registration\Entity\RegistrationTypeInterface[]
   *   The enabled types.
   */
  public function getEnabledRegistrationTypes(EventInterface $event);

  /**
   * Get availabe registration types for an event.
   *
   * This function checks a users access to register for the specific types.
   *
   * @param \Drupal\event\Entity\EventInterface $event
   *   The event.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user instance.
   *
   * @return \Drupal\event_registration\Entity\RegistrationTypeInterface[]
   *   The enabled types.
   */
  public function getAvailableRegistrationTypes(EventInterface $event, AccountInterface $account);

}
