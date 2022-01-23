<?php

namespace Drupal\adimeo_apm_tracking\Plugin\ApmTracking;

use Drupal\adimeo_apm_tracking\Annotation\ApmTracking;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingBase;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingInterface;

/**
 *  Site infos
 *
 * @ApmTracking(
 *  id = "site_infos",
 *  label = "Site general informations"
 * )
 */
class SiteInfosApmTracking extends ApmTrackingBase implements ApmTrackingInterface
{

    /**
     * @inheritDoc
     */
    public function fetch()
    {
      $config = \Drupal::config('system.site');
      $siteData = $config->get();

      return $siteData;
    }
}
