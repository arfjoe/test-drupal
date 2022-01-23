<?php


namespace Drupal\cp22_saulnier_home\Gateway;


use Drupal\adimeo_tools\Service\LanguageService;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\NodeInterface;

class HomeGateway
{

  const HOME_NODE_BUNDLE = 'home';

  /**
   * @var EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var LanguageService
   */
  protected $languageService;


  public function __construct(EntityTypeManager $entityTypeManager, LanguageService $languageService) {
    $this->entityTypeManager = $entityTypeManager;
    $this->languageService = $languageService;
  }

  /**
   * Retourne l'id du node de la page d'accueil en fonction de la langue courante
   * @return int|null
   */
  public function getFrontPageNodeId() {
    try {
      $query = $this->entityTypeManager->getStorage('node')->getQuery();
    }
    catch (InvalidPluginDefinitionException | PluginNotFoundException $exception) {
      return NULL;
    }
    $query->condition('type', self::HOME_NODE_BUNDLE, '=', $this->languageService->getCurrentLanguageId());
    $query->condition('status', NodeInterface::PUBLISHED);
    $query->sort('nid', 'DESC');
    $query->range(0,1);

    $queryResult = $query->execute();

    if (!empty($queryResult)) {
      return reset($queryResult);
    }
    return NULL;
  }

}
