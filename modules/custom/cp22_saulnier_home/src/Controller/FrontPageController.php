<?php

namespace Drupal\cp22_saulnier_home\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\cp22_saulnier_home\Manager\HomeManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontPageController extends ControllerBase{

  const ROUTE_NAME = "cp22_saulnier_home.home";


  /**
   * @var HomeManager
   */
  protected $manager;

  public function __construct(HomeManager $manager) {
    $this->manager = $manager;
  }

  /**
   * Renvoi le contenu à rendre pour la page d'accueil
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function content() {

    $home = $this->manager->getFrontPageNodeView();
    if(!empty($home)){
      return $home;
    }

    // IF NO CONTENT
    throw new NotFoundHttpException();
  }

}
