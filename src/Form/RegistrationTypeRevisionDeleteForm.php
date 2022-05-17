<?php

namespace Drupal\event_registration\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Registration type revision.
 *
 * @ingroup event_registration
 */
class RegistrationTypeRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Registration type revision.
   *
   * @var \Drupal\event_registration\Entity\RegistrationTypeInterface
   */
  protected $revision;

  /**
   * The Registration type storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $registrationTypeStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->registrationTypeStorage = $container->get('entity_type.manager')->getStorage('event_registration_type');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_registration_type_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.event_registration_type.version_history', ['event_registration_type' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $event_registration_type_revision = NULL) {
    $this->revision = $this->RegistrationTypeStorage->loadRevision($event_registration_type_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->RegistrationTypeStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Registration type: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Registration type %title has been deleted.', ['%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.event_registration_type.canonical',
       ['event_registration_type' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {event_registration_type_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.event_registration_type.version_history',
         ['event_registration_type' => $this->revision->id()]
      );
    }
  }

}
