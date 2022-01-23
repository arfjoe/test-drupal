<?php
namespace Drupal\cp_blog_post\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

/**
* Le controller de la page blog
*/
class BlogController extends ControllerBase {

  /**
  * Retourne un "render-able array" pour la page blog.
  */
  public function content() {
    $limit = 12;
    $build = [
      '#markup' => '<p>'. $this->t("Pas de résultat") . '</p>'
    ];

    $entityTypeManager = Drupal::entityTypeManager();
    $nodeStorage = $entityTypeManager->getStorage('node');

    $query = $nodeStorage->getQuery();
    $query->condition('type', 'blog_post')
      ->condition('status',TRUE)
      ->sort('changed', 'DESC');

    $query->pager($limit);

    $nids = $query->execute();

    if (!empty($nids)) {
      $nodeObjects = $nodeStorage->loadMultiple($nids);
      $nodeViewBuilder = $entityTypeManager->getViewBuilder('node');
      $view_mode = 'teaser';

      $nodes = [];
      foreach ($nodeObjects as $node) {
        $nid = $node->id();
        $nodes[$nid] = $nodeViewBuilder->view($node, $view_mode);
      }

      $build = [
        'wrapper' => [
          '#type' => 'container',
          '#attributes' => [
            'class' => [
              'BlogList-wrapper'
            ],
          ],
          'nodes' => [
            // Alternative en utilisant un thème custom avec un template plutot que "item_list"
            //  '#theme' => 'cp_blog_post_blog_list',
            //  '#nodes' => $nodes,
            '#theme' => 'item_list',
            '#list_type' => 'ul',
            '#title' => '',
            '#items' => $nodes,
            '#attributes' => ['class' => 'BlogList'],
          ],
          'pager' => [
            '#type' => 'pager',
          ]
        ],
      ];
    }

    return $build;
  }

}
