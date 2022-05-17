<?php

namespace Drupal\event_registration\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Registration type type entity.
 *
 * @ConfigEntityType(
 *   id = "event_registration_type_bundle",
 *   label = @Translation("Registration type type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\event_registration\RegistrationTypeBundleListBuilder",
 *     "form" = {
 *       "add" = "Drupal\event_registration\Form\RegistrationTypeBundleForm",
 *       "edit" = "Drupal\event_registration\Form\RegistrationTypeBundleForm",
 *       "delete" = "Drupal\event_registration\Form\RegistrationTypeBundleDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\event_registration\RegistrationTypeBundleHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "event_registration_type_bundle",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "event_registration_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/event/registration/type/bundle/{event_registration_type_bundle}",
 *     "add-form" = "/admin/structure/event/registration/type/bundle/add",
 *     "edit-form" = "/admin/structure/event/registration/type/bundle/{event_registration_type_bundle}/edit",
 *     "delete-form" = "/admin/structure/event/registration/type/bundle/{event_registration_type_bundle}/delete",
 *     "collection" = "/admin/structure/event/registration/type/bundle"
 *   }
 * )
 */
class RegistrationTypeBundle extends ConfigEntityBundleBase implements RegistrationTypeBundleInterface {

  /**
   * The Registration type type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Registration type type label.
   *
   * @var string
   */
  protected $label;

}
