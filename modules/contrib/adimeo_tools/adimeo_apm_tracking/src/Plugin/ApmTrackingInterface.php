<?php

namespace Drupal\adimeo_apm_tracking\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Apm tracking plugins.
 */
interface ApmTrackingInterface extends PluginInspectionInterface {

  public function label();


  /**
   * Return the requested data
   *
   * @return array
   */
 public function fetch();

}
