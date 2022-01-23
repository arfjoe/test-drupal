<?php

namespace Drupal\adimeo_tools\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Executes exif rotation on images (file entity) through module exif_orientation method
 *
 * @QueueWorker(
 *   id = "images_apply_exif_rotation",
 *   title = @Translation("Apply exif orientation on images with module exif_orientation"),
 *   cron = {"time" = 10}
 * )
 */
class ImageApplyExifRotation extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * @var LoggerChannelFactoryInterface
   */
  protected $loggerChannelFactory;

  /**
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entityTypeManager,
                              LoggerChannelFactoryInterface $loggerChannelFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->loggerChannelFactory = $loggerChannelFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('logger.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    if (is_numeric($data)) {
      try {
        $file = $this->entityTypeManager->getStorage('file')->load($data);
        _exif_orientation_rotate($file);

      } catch (EntityStorageException $e) {
        $this->loggerChannelFactory->get('adimeo_tools')->notice($e->getMessage());
      }
    }
  }

}
