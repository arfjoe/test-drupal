<?php

namespace Drupal\adimeo_apm_tracking\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Apm tracking plugin manager.
 */
class ApmTrackingManager extends DefaultPluginManager {


  /**
   * Constructs a new ApmTrackingPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ApmTracking', $namespaces, $module_handler, 'Drupal\adimeo_apm_tracking\Plugin\ApmTrackingInterface', 'Drupal\adimeo_apm_tracking\Annotation\ApmTracking');

    $this->alterInfo('adimeo_apm_tracking_apm_tracking_info');
    $this->setCacheBackend($cache_backend, 'adimeo_apm_tracking_apm_tracking');
  }

}
