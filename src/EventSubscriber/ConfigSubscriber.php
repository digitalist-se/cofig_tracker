<?php

namespace Drupal\config_tracker\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Serialization\Yaml;


/**
 * Class ConfigSubscriber.
 *
 * @package Drupal\config_tracker
 */
class ConfigSubscriber implements EventSubscriberInterface {


  /**
   * Constructor.
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events['config.save'] = ['configSave'];
    $events['config.delete'] = ['configDelete'];

    //$events[ConfigEvents::SAVE][] = ['configSave', 11];
    //$events[ConfigEvents::DELETE][] = ['configDelete', 11];
    return $events;
  }

  /**
   * This method is called whenever the config.save event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function configSave(ConfigCrudEvent $event) {
    // Check which config that was changed.
    //var_dump($getOriginal);
    // Config could be new or changed.
    $state = 'changed';
    $config = $event->getConfig();
    $isNew = $config->isNew();
    if ($isNew === true) {
      $state =  'created';
    }

    $getRawConfig = $config->getRawData();
    $rawConfig = json_encode($getRawConfig);
    $orginalConfig = json_encode($config->getOriginal());
    $orginalConfig = [];

    $configName = $config->getName();
    // Get which user who changed the config.
    $user = \Drupal::currentUser();
    $name = $user->getAccount()->getDisplayName();
    \Drupal::logger('config_tracker')->notice(t("@configName config @state by @user from: @original to @to"), [
      '@state' => $state,
      '@configName' => $configName,
      '@user' => $name,
      '@original' => $orginalConfig,
      '@to' => json_decode($rawConfig),
     ]);
  }

  /**
   * This method is called whenever the config.delete event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function configDelete(ConfigCrudEvent $event) {
    // Check which config that was changed.
    $config = $event->getConfig();
    $configName = $config->getName();
    // Get which user who changed the config.
    $user = \Drupal::currentUser();
    $name = $user->getAccount()->getDisplayName();

    \Drupal::logger('config_tracker')->notice("@configName config deleted by @user", [
      '@configName' => $configName,
      '@user' => $name,
    ]);
  }

}
