<?php

namespace Drupal\config_tracker\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Tracker entity entities.
 *
 * @ingroup config_tracker
 */
interface TrackerEntityInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Tracker entity name.
   *
   * @return string
   *   Name of the Tracker entity.
   */
  public function getName();

  /**
   * Sets the Tracker entity name.
   *
   * @param string $name
   *   The Tracker entity name.
   *
   * @return \Drupal\config_tracker\Entity\TrackerEntityInterface
   *   The called Tracker entity entity.
   */
  public function setName($name);

  /**
   * Gets the Tracker entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Tracker entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Tracker entity creation timestamp.
   *
   * @param int $timestamp
   *   The Tracker entity creation timestamp.
   *
   * @return \Drupal\config_tracker\Entity\TrackerEntityInterface
   *   The called Tracker entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Tracker entity published status indicator.
   *
   * Unpublished Tracker entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Tracker entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Tracker entity.
   *
   * @param bool $published
   *   TRUE to set this Tracker entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\config_tracker\Entity\TrackerEntityInterface
   *   The called Tracker entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the Tracker entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Tracker entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\config_tracker\Entity\TrackerEntityInterface
   *   The called Tracker entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Tracker entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Tracker entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\config_tracker\Entity\TrackerEntityInterface
   *   The called Tracker entity entity.
   */
  public function setRevisionUserId($uid);

}
