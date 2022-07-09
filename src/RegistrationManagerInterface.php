<?php

namespace Drupal\event_registration;

use Drupal\Core\Session\AccountInterface;
use Drupal\event\Entity\EventInterface;

/**
 *
 */
interface RegistrationManagerInterface {

  /**
   *
   */
  public function canRegisterFor(EventInterface $event, AccountInterface $account);

  /**
   *
   */
  public function getEnabledRegistrationTypes(EventInterface $event);

  /**
   *
   */
  public function getAvailableRegistrationTypes(EventInterface $event, AccountInterface $account);

}
