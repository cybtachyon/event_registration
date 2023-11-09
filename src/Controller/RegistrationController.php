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
class RegistrationController extends ControllerBase implements ContainerInjectionInterface {

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
  public function revisionShow($event_registration_revision) {
    $event_registration = $this->entityTypeManager()->getStorage('event_registration')
      ->loadRevision($event_registration_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('event_registration');

    return $view_builder->view($event_registration);
  }

  /**
   * Page title callback for a Registration revision.
   *
   * @param int $event_registration_revision
   *   The Registration revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($event_registration_revision) {
    $event_registration = $this->entityTypeManager()->getStorage('event_registration')
      ->loadRevision($event_registration_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $event_registration->label(),
      '%date' => $this->dateFormatter->format($event_registration->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Registration.
   *
   * @param \Drupal\event_registration\Entity\RegistrationInterface $event_registration
   *   A Registration object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(RegistrationInterface $event_registration) {
    $account = $this->currentUser();
    $event_registration_storage = $this->entityTypeManager()->getStorage('event_registration');

    $langcode = $event_registration->language()->getId();
    $langname = $event_registration->language()->getName();
    $languages = $event_registration->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $event_registration->label(),
    ]) : $this->t('Revisions for %title', [
      '%title' => $event_registration->label(),
    ]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all event_registration revisions") || $account->hasPermission('administer event_registration entities')));
    $delete_permission = (($account->hasPermission("delete all event_registration revisions") || $account->hasPermission('administer event_registration entities')));

    $rows = [];

    $vids = $event_registration_storage->revisionIds($event_registration);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\event_registration\RegistrationInterface $revision */
      $revision = $event_registration_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $event_registration->getRevisionId()) {
          $link = $this->l($date, new Url('entity.event_registration.revision', [
            'event_registration' => $event_registration->id(),
            'event_registration_revision' => $vid,
          ]));
        }
        else {
          $link = $event_registration->toLink($date)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.event_registration.translation_revert', [
                'event_registration' => $event_registration->id(),
                'event_registration_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.event_registration.revision_revert', [
                'event_registration' => $event_registration->id(),
                'event_registration_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.event_registration.revision_delete', [
                'event_registration' => $event_registration->id(),
                'event_registration_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['event_registration_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
