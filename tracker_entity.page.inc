<?php

/**
 * @file
 * Contains tracker_entity.page.inc.
 *
 * Page callback for Tracker entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Tracker entity templates.
 *
 * Default template: tracker_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_tracker_entity(array &$variables) {
  // Fetch TrackerEntity Entity Object.
  $tracker_entity = $variables['elements']['#tracker_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
