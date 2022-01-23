<?php


namespace Drupal\cp22_saulnier_global\Service;


use Drupal;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Routing\AdminContext;
use Drupal\Core\Controller\TitleResolver;

/**
 * Provides a base breadcrumb builder
 */
class BaseBreadcrumb implements BreadcrumbBuilderInterface {

  use StringTranslationTrait;

  /**
   * @var AdminContext
   */
  protected $adminContext;

  /**
   * @var TitleResolver
   */
  protected $titleResolver;

  /**
   * constructor.
   *
   * @param AdminContext $adminContext
   * @param TitleResolver $titleResolver
   */
  public function __construct(AdminContext $adminContext, TitleResolver $titleResolver) {
    $this->adminContext = $adminContext;
    $this->titleResolver = $titleResolver;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $routeMatch) {
    return !$this->adminContext->isAdminRoute();
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $routeMatch) {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addLink(Link::createFromRoute($this->t('Home'), '<front>'));

    $request     = Drupal::request();
    $title       = $this->titleResolver->getTitle($request, $routeMatch->getRouteObject());

    $breadcrumb->addLink(Link::createFromRoute($title, '<nolink>'));

    return $breadcrumb;
  }

}
