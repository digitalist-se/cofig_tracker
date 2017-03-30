<?php

namespace Drupal\config_tracker\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Tracker entity entities.
 */
class TrackerEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
