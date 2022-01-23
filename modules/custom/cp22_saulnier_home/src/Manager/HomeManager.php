<?php

namespace Drupal\cp22_saulnier_home\Manager;

use Drupal;
use Drupal\adimeo_tools\Service\LanguageService;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\cp22_saulnier_home\Gateway\HomeGateway;


class HomeManager
{
  const SERVICE_NAME = 'cp22_saulnier_home.home_manager';

  /**
   * @var EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var LanguageService
   */
  protected $languageService;

  /**
   * @var HomeGateway
   */
  protected $homeGateway;

  /**
   * Retourne le service (quand pas d'injection de dépendances possible)
   * @return static;
   */
  public static function me() {
    return Drupal::service(static::SERVICE_NAME);
    //$homeManager = HomeManager::me();
  }

  public function __construct(EntityTypeManager $entityTypeManager, LanguageService $languageService, HomeGateway $homeGateway) {
    $this->entityTypeManager = $entityTypeManager;
    $this->languageService = $languageService;
    $this->homeGateway = $homeGateway;
  }

  public function getFrontPageNodeId() {
    return $this->homeGateway->getFrontPageNodeId();
  }

  public function getFrontPageNodeView() {
    $homeId = $this->getFrontPageNodeId();

    if(!empty($homeId)){
      $view_builder = $this->entityTypeManager->getViewBuilder('node');
      $homeNode = $this->languageService->load('node',$homeId);
      $home = $view_builder->view($homeNode);
      if(!empty($home)){
        return $home;
      }
    }

    return [];
  }

}
