<?php

namespace Drupal\config_tracker;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class TrackerEntityStorage extends SqlContentEntityStorage implements TrackerEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(TrackerEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {tracker_entity_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {tracker_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(TrackerEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {tracker_entity_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('tracker_entity_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
