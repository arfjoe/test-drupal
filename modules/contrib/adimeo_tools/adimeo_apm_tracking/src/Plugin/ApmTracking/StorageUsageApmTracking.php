<?php

namespace Drupal\adimeo_apm_tracking\Plugin\ApmTracking;

use Drupal\adimeo_apm_tracking\Annotation\ApmTracking;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingBase;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingInterface;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  Storage usage infos
 *
 * @ApmTracking(
 *  id = "site_storage_usage",
 *  label = "Site storage usage"
 * )
 */
class StorageUsageApmTracking extends ApmTrackingBase implements ApmTrackingInterface, ContainerFactoryPluginInterface
{


  /**
   * @var FileSystem
   */
  private $fileSystem;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, FileSystem $fileSystem)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->fileSystem = $fileSystem;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('file_system')
    );
  }

  /**
   * @inheritDoc
   */
  public function fetch()
  {

    $shellData = exec('du -sk ' . $this->fileSystem->realpath('://'));
    $filesize = preg_split('/[\t]/', $shellData);

    // convert bytes to megabytes
    $readableFileSize = floor($filesize[0] / 1024);


    return $readableFileSize;
  }


}
