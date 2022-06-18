<?php

namespace Drupal\event_registration\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\event_registration\Entity\RegistrationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RegistrationController.
 *
 *  Returns responses for Registration routes.
 */
class RegisterController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

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
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Registration revision.
   *
   * @param int $event_registration_revision
   *   The Registration revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function register($event_registration_revision) {
    $event_registration = $this->entityTypeManager()->getStorage('event_registration')
      ->loadRevision($event_registration_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('event_registration');

    return $view_builder->view($event_registration);
  }

}
