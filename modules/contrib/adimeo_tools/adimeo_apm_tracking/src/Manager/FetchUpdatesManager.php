<?php


namespace Drupal\adimeo_apm_tracking\Manager;


use Drupal\adimeo_apm_tracking\Manager\Interfaces\FetchUpdatesInterface;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingBase;

abstract class FetchUpdatesManager extends ApmTrackingBase implements FetchUpdatesInterface {

  /**
   * @param array $project
   *
   * @return string
   */
  public function getUpdate(array $project): string
  {
    return $project['title'] . ' (<a href="'. $project['link'] .'">' . $project['name'] . '</a>) : ' . $project['info']['version'] . ' => ' . $project['recommended'];
  }

}
