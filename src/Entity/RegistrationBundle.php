<?php

namespace Drupal\event_registration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Registration type entity.
 *
 * @ConfigEntityType(
 *   id = "event_registration_bundle",
 *   label = @Translation("Registration type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\event_registration\RegistrationBundleListBuilder",
 *     "form" = {
 *       "add" = "Drupal\event_registration\Form\RegistrationBundleForm",
 *       "edit" = "Drupal\event_registration\Form\RegistrationBundleForm",
 *       "delete" = "Drupal\event_registration\Form\RegistrationBundleDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\event_registration\RegistrationBundleHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "event_registration_bundle",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "event_registration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/event/registration/registration/bundle/{event_registration_bundle}",
 *     "add-form" = "/admin/structure/event/registration/registration/bundle/add",
 *     "edit-form" = "/admin/structure/event/registration/registration/bundle/{event_registration_bundle}/edit",
 *     "delete-form" = "/admin/structure/event/registration/registration/bundle/{event_registration_bundle}/delete",
 *     "collection" = "/admin/structure/event/registration/registration/bundle"
 *   }
 * )
 */
class RegistrationBundle extends ConfigEntityBundleBase implements RegistrationBundleInterface {

  /**
   * The Registration type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Registration type label.
   *
   * @var string
   */
  protected $label;

}
