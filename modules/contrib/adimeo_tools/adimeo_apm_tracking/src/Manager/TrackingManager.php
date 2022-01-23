<?php


namespace Drupal\adimeo_apm_tracking\Manager;

use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingManager;
use Drupal\Core\Config\ConfigFactoryInterface;

class TrackingManager
{

  /**
   * @var ApmTrackingManager
   */
  private $apmTrackingManager;
  /**
   * @var ConfigFactoryInterface
   */
  private $config;

  public function __construct(ApmTrackingManager $manager, ConfigFactoryInterface $config)
  {
    $this->apmTrackingManager = $manager;
    $this->config = $config;
  }

  /**
   * Check if cron job has been executed today
   *
   * @return boolean
   */
  public function shouldRunCron()
  {
    $lastTrackingDateCron = \Drupal::state()->get('adimeo_apm_tracking_last_cron');

    if (date('d-m-Y') != $lastTrackingDateCron) {
      \Drupal::state()->set('adimeo_apm_tracking_last_cron', date('d-m-Y'));
      return true;
    }

    return false;
  }

  /**
   * get data from all plugins type ApmTracking with the fetch method
   *
   * @return array
   */
  public function fetchData()
  {

    $instances = $this->createPluginInstances();

    $data = array();
    foreach ($instances as $instance) {
      $data[$instance->id()] = $instance->fetch();
    }

    // todo check if is still useful, given it is a pull method.
//    $data = array_merge($data, $this->fetchConfigData());

    return $data;
  }


  public function fetchConfigData()
  {
    return [
      'site_id' => $this->config->get('apm_tracking_id'),
      'site_environnement' => $this->config->get('apm_tracking_environnement'),
      'sending_method' => $this->config->get('apm_tracking_sending_method'),
    ];
  }

  /**
   *  Create instances for all apmTracking plugins
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function createPluginInstances()
  {
    $definitions = $this->apmTrackingManager->getDefinitions();

    $instances = array();
    foreach ($definitions as $plugin_id => $plugin_definition) {

      $instances[] = $this->apmTrackingManager->createInstance($plugin_id, []);

    }

    return $instances;
  }

}
