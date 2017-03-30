<?php

namespace Drupal\config_tracker;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Tracker entity entity.
 *
 * @see \Drupal\config_tracker\Entity\TrackerEntity.
 */
class TrackerEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\config_tracker\Entity\TrackerEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished tracker entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published tracker entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit tracker entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete tracker entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add tracker entity entities');
  }

}
