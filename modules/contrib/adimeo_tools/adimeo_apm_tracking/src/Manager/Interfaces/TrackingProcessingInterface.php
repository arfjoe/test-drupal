<?php

namespace Drupal\adimeo_apm_tracking\Manager\Interfaces;

interface TrackingProcessingInterface
{
  public function send(array $data);

}
