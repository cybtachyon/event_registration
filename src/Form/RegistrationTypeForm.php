<?php

namespace Drupal\event_registration\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RegistrationTypeForm.
 */
class RegistrationTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $event_registration_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $event_registration_type->label(),
      '#description' => $this->t("Label for the Registration Type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $event_registration_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\event_registration\Entity\RegistrationType::load',
      ],
      '#disabled' => !$event_registration_type->isNew(),
    ];

    $form['description'] = [
      '#title' => $this->t('Description'),
      '#type' => 'textarea',
      '#default_value' => $event_registration_type->getDescription(),
    ];
    
    $profile_types = $this->entityTypeManager->getStorage('profile_type')->loadMultiple();
    $profile_options = array_map(function ($profile_type) {
      return $profile_type->label();
    }, $profile_types);

    $form['profileType'] = [
      '#type' => 'select',
      '#title' => $this->t('Profile Type'),
      '#description' => $this->t('The target profile type for registration form.'),
      '#options' => $profile_options,
      '#default_value' => $event_registration_type->getProfileTypeId(),
    ];
    
    $definitions = $this->entityTypeManager->getDefinitions();
    $type_options = array_map(function ($definition) {
      return $definition->getLabel();
    }, $definitions);

    $form['targetEntityType'] = [
      '#type' => 'select',
      '#title' => $this->t('Registerable Entity Type'),
      '#description' => $this->t('The type of entity that can be registered with this registration type.'),
      '#options' => $type_options,
      '#default_value' => $event_registration_type->getTargetEntityTypeId() ?? 'user',
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $event_registration_type = $this->entity;
    $status = $event_registration_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Registration Type.', [
          '%label' => $event_registration_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Registration Type.', [
          '%label' => $event_registration_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($event_registration_type->toUrl('collection'));
  }

}
