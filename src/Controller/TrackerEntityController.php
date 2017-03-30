<?php

namespace Drupal\config_tracker\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\config_tracker\Entity\TrackerEntityInterface;

/**
 * Class TrackerEntityController.
 *
 *  Returns responses for Tracker entity routes.
 *
 * @package Drupal\config_tracker\Controller
 */
class TrackerEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Tracker entity  revision.
   *
   * @param int $tracker_entity_revision
   *   The Tracker entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($tracker_entity_revision) {
    $tracker_entity = $this->entityManager()->getStorage('tracker_entity')->loadRevision($tracker_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('tracker_entity');

    return $view_builder->view($tracker_entity);
  }

  /**
   * Page title callback for a Tracker entity  revision.
   *
   * @param int $tracker_entity_revision
   *   The Tracker entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($tracker_entity_revision) {
    $tracker_entity = $this->entityManager()->getStorage('tracker_entity')->loadRevision($tracker_entity_revision);
    return $this->t('Revision of %title from %date', array('%title' => $tracker_entity->label(), '%date' => format_date($tracker_entity->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Tracker entity .
   *
   * @param \Drupal\config_tracker\Entity\TrackerEntityInterface $tracker_entity
   *   A Tracker entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(TrackerEntityInterface $tracker_entity) {
    $account = $this->currentUser();
    $langcode = $tracker_entity->language()->getId();
    $langname = $tracker_entity->language()->getName();
    $languages = $tracker_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $tracker_entity_storage = $this->entityManager()->getStorage('tracker_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $tracker_entity->label()]) : $this->t('Revisions for %title', ['%title' => $tracker_entity->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all tracker entity revisions") || $account->hasPermission('administer tracker entity entities')));
    $delete_permission = (($account->hasPermission("delete all tracker entity revisions") || $account->hasPermission('administer tracker entity entities')));

    $rows = array();

    $vids = $tracker_entity_storage->revisionIds($tracker_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\config_tracker\TrackerEntityInterface $revision */
      $revision = $tracker_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $tracker_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.tracker_entity.revision', ['tracker_entity' => $tracker_entity->id(), 'tracker_entity_revision' => $vid]));
        }
        else {
          $link = $tracker_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->revision_log_message->value, '#allowed_tags' => Xss::getHtmlTagList()],
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
              Url::fromRoute('entity.tracker_entity.translation_revert', ['tracker_entity' => $tracker_entity->id(), 'tracker_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.tracker_entity.revision_revert', ['tracker_entity' => $tracker_entity->id(), 'tracker_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.tracker_entity.revision_delete', ['tracker_entity' => $tracker_entity->id(), 'tracker_entity_revision' => $vid]),
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

    $build['tracker_entity_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
