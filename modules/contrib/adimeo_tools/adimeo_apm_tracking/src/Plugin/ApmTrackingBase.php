<?php

namespace Drupal\adimeo_apm_tracking\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Apm tracking plugins.
 */
abstract class ApmTrackingBase extends PluginBase implements ApmTrackingInterface {

  /**
   * Retrieve the @label property from the annotation and return it.
   *
   * @return mixed
   */
  public function label() {
    return $this->pluginDefinition['label'];
  }

  /**
   * Retrieve the @id property from the annotation and return it.
   *
   * @return mixed
   */
  public function id() {
    return $this->pluginDefinition['id'];
  }


}
