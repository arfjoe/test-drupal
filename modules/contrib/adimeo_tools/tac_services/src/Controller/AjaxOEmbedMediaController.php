<?php


namespace Drupal\tac_services\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Drupal\media\Entity\Media;
use Drupal\tac_services\Manager\TacMediaManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AjaxOEmbedMediaController extends ControllerBase
{

  /**
   * @var TacMediaManager
   */
  protected $manager;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * AjaxOEmbedMediaController constructor.
   *
   * @param \Drupal\tac_services\Manager\TacMediaManager $manager
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(TacMediaManager $manager,RendererInterface $renderer) {
    $this->manager = $manager;
    $this->renderer = $renderer;
  }

  /**
   * @param Request $request
   * @param Media $media
   * @param string $field_name
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function replaceWithOembedContent(Request $request, Media $media, string $field_name)
  {
    if($request->isXmlHttpRequest()) {
      $build = $this->manager->buildMediaOEmendedFieldDisplay($media,$field_name);

      if (!empty($build)) {
        $mediaId = $media->id();
        $selector = '.tac-media-oembed-placeholder[data-media-id="'. $mediaId .'"]' ;

        $response = new AjaxResponse();
        $response->addCommand(new HtmlCommand($selector,$build));
        return $response;
      }
    }

    // IF NOT AN AJAX CALL OR BUILD EMPTY
    throw new NotFoundHttpException();
  }

  /**
   * @param Request $request
   * @param Media $media
   * @param string $field_name
   *
   * @return Response
   */
  public function returnOembedContentForCkEditor(Request $request, Media $media, string $field_name)
  {
    $build = $this->manager->buildMediaOEmendedFieldDisplay($media,$field_name);

    if (!empty($build)) {
      $html = $this->renderer->renderPlain($build);

      // Note that we intentionally do not use:
      // - \Drupal\Core\Cache\CacheableResponse because caching it on the server
      //   side is wasteful, hence there is no need for cacheability metadata.
      // - \Drupal\Core\Render\HtmlResponse because there is no need for
      //   attachments nor cacheability metadata.
      return (new Response($html))
        // Do not allow any intermediary to cache the response, only the end user.
        ->setPrivate()
        // Allow the end user to cache it for up to 5 minutes.
        ->setMaxAge(300);
    }

    // If empty build
    throw new NotFoundHttpException();
  }
}
