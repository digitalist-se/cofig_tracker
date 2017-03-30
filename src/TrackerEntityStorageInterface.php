<?php

namespace Drupal\config_tracker;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\config_tracker\Entity\TrackerEntityInterface;

/**
 * Defines the storage handler class for Tracker entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Tracker entity entities.
 *
 * @ingroup config_tracker
 */
interface TrackerEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Tracker entity revision IDs for a specific Tracker entity.
   *
   * @param \Drupal\config_tracker\Entity\TrackerEntityInterface $entity
   *   The Tracker entity entity.
   *
   * @return int[]
   *   Tracker entity revision IDs (in ascending order).
   */
  public function revisionIds(TrackerEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Tracker entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Tracker entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\config_tracker\Entity\TrackerEntityInterface $entity
   *   The Tracker entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(TrackerEntityInterface $entity);

  /**
   * Unsets the language for all Tracker entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
