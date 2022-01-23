<?php

namespace Drupal\cp22_saulnier_global;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\NodeInterface;
use Drupal\adimeo_tools\Service\LanguageService;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Url;

abstract class ModuleContentServiceBase {

  abstract protected function getListTypeId();

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var LanguageService
   */
  protected $languageService;

  /**
   * @var LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * @var
   */
  protected $type;


  /**
   *  constructor.
   *
   * @param EntityTypeManager $entityTypeManager
   *   Instance of EntityTypeManager.
   * @param LanguageService $languageService
   * @param LanguageManagerInterface $languageManager
   */
  public function __construct(EntityTypeManager $entityTypeManager, LanguageService $languageService, LanguageManagerInterface $languageManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->languageService = $languageService;
    $this->languageManager = $languageManager;
  }

  /**
   * Return nid of page list.
   *
   * @return null|int
   */
  public function getListPageId() {
    try {
      $query = $this->entityTypeManager->getStorage('node')->getQuery();
    }
    catch (InvalidPluginDefinitionException | PluginNotFoundException $exception) {
      return NULL;
    }
    $query->condition('type', $this->getListTypeId());
    $query->condition('langcode', $this->languageService->getCurrentLanguageId());
    $query->range(0, 1);
    $query->condition('status', NodeInterface::PUBLISHED);
    $query->sort('changed', 'DESC');

    $nids = $query->execute();
    if (!empty($nids)) {
      return reset($nids);
    }

    return NULL;
  }

  /**
   * @param null $language
   *
   * @return \Drupal\Core\GeneratedUrl|null|string
   */
  public function getListPageUrl($language = NULL) {

    $pageListId = $this->getListPageId();

    if($pageListId) {
      if($language == NULL) {
        $language = $this->languageManager->getCurrentLanguage(LanguageInterface::TYPE_CONTENT);
      }
      $pageListUrl = Url::fromRoute('entity.node.canonical', ['node' => $pageListId],['language' => $language])->toString();

      return $pageListUrl;
    }
    return NULL;
  }

}
