<?php

namespace Drupal\adimeo_apm_ticketing\Manager;

use Drupal;
use Drupal\adimeo_apm_ticketing\EventSubscriber\LoginSubscriber;
use Drupal\Core\Render\Renderer;

class RenderManager
{

  const SERVICE_NAME = 'adimeo_apm_ticketing.render';


  /**
   * @var Renderer
   */
  private $renderer;

  public function __construct(Renderer $renderer)
  {
    $this->renderer = $renderer;
  }

  /**
   * Return a Singleton
   *
   * @return static
   */
  public static function me() {
    return Drupal::service(static::SERVICE_NAME);
  }

  /**
   * Return a render array with the react root div to attach
   *
   * @return array
   */
  public function renderDiv()
  {
    return [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'id' => 'apm-root',
      ],
    ];
  }

  /**
   * Return a render array with the library to attach
   *
   * @return array
   */
  public function attachLibraries()
  {
    $build = [
      '#attached' => [
        'library' => [
          'adimeo_apm_ticketing/apm_ticketing'
        ]
      ]
    ];

    return $build;
  }

  /**
   * Check if user meet all conditions to use the module
   *
   * @return bool
   */
  public function checkUserRights()
  {
    if (empty($_COOKIE['user-is-rapporteur-apm'])) {

      return false;

    } else if (!$_COOKIE['user-is-rapporteur-apm'] === LoginSubscriber::COOKIE_VALUE) {

      return false;

    }
    return true;
  }

}
