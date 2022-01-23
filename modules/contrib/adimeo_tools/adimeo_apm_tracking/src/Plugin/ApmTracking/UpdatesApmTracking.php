<?php

namespace Drupal\adimeo_apm_tracking\Plugin\ApmTracking;

use Drupal\adimeo_apm_tracking\Annotation\ApmTracking;
use Drupal\adimeo_apm_tracking\Manager\FetchUpdatesManager;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingBase;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\update\UpdateManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  Updates infos
 *
 * @ApmTracking(
 *  id = "site_updates",
 *  label = "Site available updates"
 * )
 */
class UpdatesApmTracking extends FetchUpdatesManager implements ApmTrackingInterface, ContainerFactoryPluginInterface
{

  /**
   * @var UpdateManagerInterface
   */
  private $updateManager;


  public function __construct(array $configuration, $plugin_id, $plugin_definition, UpdateManagerInterface $updateManager)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->updateManager = $updateManager;
  }


  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('update.manager')
    );
  }


  /**
   * @inheritDoc
   */
  public function fetch()
  {
    $projects = $this->updateManager->projectStorage('update_project_data');

    $updates = array();
    foreach ($projects as $project) {
      if ($project['status'] === $this->updateManager::NOT_CURRENT) {
        $updates[] = $this->getUpdate($project);
      }
    }

    return $updates;
  }


}
