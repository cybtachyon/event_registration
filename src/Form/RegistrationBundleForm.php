<?php

namespace Drupal\event_registration\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RegistrationBundleForm.
 */
class RegistrationBundleForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $event_registration_bundle = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $event_registration_bundle->label(),
      '#description' => $this->t("Label for the Registration type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $event_registration_bundle->id(),
      '#machine_name' => [
        'exists' => '\Drupal\event_registration\Entity\RegistrationBundle::load',
      ],
      '#disabled' => !$event_registration_bundle->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $event_registration_bundle = $this->entity;
    $status = $event_registration_bundle->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Registration type.', [
          '%label' => $event_registration_bundle->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Registration type.', [
          '%label' => $event_registration_bundle->label(),
        ]));
    }
    $form_state->setRedirectUrl($event_registration_bundle->toUrl('collection'));
  }

}
