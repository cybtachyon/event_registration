<?php

namespace Drupal\event_registration;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\event\Entity\EventType;
use Drupal\event\Entity\EventTypeInterface;
use Drupal\event_registration\Entity\Registration;
use Drupal\event_registration\Entity\RegistrationType;
use Drupal\event_registration\Entity\RegistrationTypeInterface;


/**
 * Provides dynamic permissions for Registration of different types.
 *
 * @ingroup event_registration
 *
 */
class RegistrationPermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of node type permissions.
   *
   * @return array
   *   The Registration by bundle permissions.
   *   @see \Drupal\user\PermissionHandlerInterface::getPermissions()
   */
  public function generatePermissions() {
    $perms = [];

    $registration_types = RegistrationType::loadMultiple();
    foreach ($registration_types as $type) {
      $perms += $this->buildPermissions($type);
    }
    
    $event_types = EventType::loadMultiple();
    foreach ($event_types as $event_type) {
      $perms += $this->buildEventPermissions($event_type, $registration_types);
    }

    return $perms;
  }

  /**
   * Returns a list of permissions for a given registration type.
   *
   * @param \Drupal\event_registration\Entity\RegistrationTypeInterface $type
   *   The Registration Type.
   *
   * @return array
   *   An associative array of permission names and descriptions.
   */
  protected function buildPermissions(RegistrationTypeInterface $type) {
    $type_id = $type->id();
    $type_params = ['%type_name' => $type->label()];

    return [
      "$type_id create entities" => [
        'title' => $this->t('Create new %type_name entities', $type_params),
      ],
      "register $type_id for event" => [
        'title' => $this->t('Register as %type_name for any events', $type_params),
      ],
      "$type_id edit own entities" => [
        'title' => $this->t('Edit own %type_name entities', $type_params),
      ],
      "$type_id edit any entities" => [
        'title' => $this->t('Edit any %type_name entities', $type_params),
      ],
      "$type_id delete own entities" => [
        'title' => $this->t('Delete own %type_name entities', $type_params),
      ],
      "$type_id delete any entities" => [
        'title' => $this->t('Delete any %type_name entities', $type_params),
      ],
      "$type_id view revisions" => [
        'title' => $this->t('View %type_name revisions', $type_params),
        'description' => $this->t('To view a revision, you also need permission to view the entity item.'),
      ],
      "$type_id revert revisions" => [
        'title' => $this->t('Revert %type_name revisions', $type_params),
        'description' => $this->t('To revert a revision, you also need permission to edit the entity item.'),
      ],
      "$type_id delete revisions" => [
        'title' => $this->t('Delete %type_name revisions', $type_params),
        'description' => $this->t('To delete a revision, you also need permission to delete the entity item.'),
      ],
    ];
  }
  
  /**
   * Returns a list of registration permissions for a given event type.
   *
   * @param \Drupal\event\Entity\EventTypeInterface $event_type
   *   The Event Type.
   * @param \Drupal\event_registration\Entity\RegistrationTypeInterface[] $registration_types
   *   The Registration Types.
   *
   * @return array
   *   An associative array of permission names and descriptions.
   */
  protected function buildEventPermissions(EventTypeInterface $event_type, array $registration_types) {
    
    $event_type_id = $event_type->id();
    $event_type_params = [
      '%event_type_name' => $event_type->label(),
    ];

    $perms = [
      "register for $event_type_id event" => [
        'title' => $this->t('Register for %event_type_name events', $event_type_params),
      ],
      "access registration overview for $event_type_id event" => [
        'title' => $this->t('Access Registration Overview for %event_type_name Event', $event_type_params),
      ],
    ];
    
    foreach ($registration_types as $registration_type) {
      $registration_type_id = $registration_type->id();
      $registration_type_param = $event_type_params + [
        '%registration_type_name' => $registration_type->label(),
      ];
      $perms += [
        "register $registration_type_id for $event_type_id event" => [
          'title' => $this->t('Register as %registration_type_name for %event_type_name events', $registration_type_param),
        ],
      ];
    }
    
    return $perms;
  }

}
