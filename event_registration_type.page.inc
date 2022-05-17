<?php

/**
 * @file
 * Contains event_registration_type.page.inc.
 *
 * Page callback for Registration type entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Registration type templates.
 *
 * Default template: event_registration_type.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_event_registration_type(array &$variables) {
  // Fetch RegistrationType Entity Object.
  $event_registration_type = $variables['elements']['#event_registration_type'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
