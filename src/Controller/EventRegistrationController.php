<?php

namespace Drupal\event_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\event\Entity\EventInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides controller for an event's registration overview page.
 */
class EventRegistrationController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The event registration manager.
   *
   * @var \Drupal\event_registration\RegistrationManagerInterface
   */
  protected $registrationManager;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->registrationManager = $container->get('event_registration.registration_manager');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * The controller title callback.
   *
   * @param \Drupal\event\Entity\EventInterface $event
   *   The routes event.
   *
   * @return string
   *   The page title.
   */
  public function overviewTitle(EventInterface $event) {
    return $this->t('%event Registration', [
      '%event' => $event->label(),
    ]);
  }

  /**
   * Displays a list of available registration types for an event.
   *
   * @param \Drupal\event\Entity\EventInterface $event
   *   The routes event.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function overview(EventInterface $event) {
    $types = $this->registrationManager->getAvailableRegistrationTypes($event, $this->currentUser());

    $view_builder = $this->entityTypeManager()->getViewBuilder('event_registration_type');

    $build = [
      'registration_types' => [
        '#type' => 'htmltag',
        '#tag' => 'dl',
      ],
    ];

    foreach ($types as $type) {
      $build['registration_types'] += [
        $type->id() . '_label' => [
          '#type' => 'htmltag',
          '#tag' => 'dt',
          'label' => [
            '#type' => 'item',
            '#markup' => $type->label(),
          ],
        ],
        $type->id() . '_details' => [
          '#type' => 'htmltag',
          '#tag' => 'dd',
          'description' => [
            '#type' => 'htmltag',
            '#tag' => 'p',
            '#value' => $type->getDescription(),
          ],
          'button' => [
            '#type' => 'link',
            '#attributes' => [
              'class' => ['btn'],
            ],
            '#title' => $this->t('Register'),
            '#url' => Url::fromRoute('entity.event_registration.register', [
              'event_registration_type' => $type->id(),
              'event' => $event->id(),
            ]),
          ],
        ],
      ];
    }
    $build['#cache']['max-age'] = 0;
    return $build;
  }

  /**
   * The controller title callback.
   *
   * @param \Drupal\event\Entity\EventInterface $event
   *   The routes event.
   *
   * @return string
   *   The page title.
   */
  public function listTitle(EventInterface $event) {
    return $this->t('%event Registrations', [
      '%event' => $event->label(),
    ]);
  }

  /**
   * Displays a list of available registration types for an event.
   *
   * @param \Drupal\event\Entity\EventInterface $event
   *   The routes event.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function list(EventInterface $event) {
    $types = $this->registrationManager->getAvailableRegistrationTypes($event, $this->currentUser());

    $view_builder = $this->entityTypeManager()->getViewBuilder('event_registration_type');

    $build = [
      'registration_types' => [
        '#type' => 'htmltag',
        '#tag' => 'dl',
      ],
    ];

    foreach ($types as $type) {
      $build['registration_types'] += [
        $type->id() . '_label' => [
          '#type' => 'htmltag',
          '#tag' => 'dt',
          'label' => [
            '#type' => 'item',
            '#markup' => $type->label(),
          ],
        ],
        $type->id() . '_details' => [
          '#type' => 'htmltag',
          '#tag' => 'dd',
          'description' => [
            '#type' => 'htmltag',
            '#tag' => 'p',
            '#value' => $type->getDescription(),
          ],
          'button' => [
            '#type' => 'link',
            '#attributes' => [
              'class' => ['btn'],
            ],
            '#title' => $this->t('Register'),
            '#url' => Url::fromRoute('entity.event_registration.register', [
              'event_registration_type' => $type->id(),
              'event' => $event->id(),
            ]),
          ],
        ],
      ];
    }
    $build['#cache']['max-age'] = 0;
    return $build;
  }

}
