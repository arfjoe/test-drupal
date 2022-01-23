<?php

namespace Drupal\adimeo_apm_ticketing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class MediaController.
 */
class MediaController extends ControllerBase
{

  /**
   * Media
   * @param $fileName
   * @return RedirectResponse
   */
  public function media(string $fileName)
  {
    return $this->redirect('adimeo_apm_ticketing.media_redirect', ['fileName' => $fileName]);
  }

  /**
   * MediaApm
   * @param string $fileName
   * @return RedirectResponse
   */
  public function mediaApm(string $fileName)
  {
    return $this->redirect('adimeo_apm_ticketing.media_apm_redirect', ['fileName' => $fileName]);
  }

}
