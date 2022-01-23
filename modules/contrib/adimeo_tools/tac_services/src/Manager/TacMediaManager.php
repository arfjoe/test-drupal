<?php


namespace Drupal\tac_services\Manager;


use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Field\Plugin\Field\FieldType\StringItem;
use Drupal\Core\Render\RendererInterface;
use Drupal\media\Entity\Media;
use InvalidArgumentException;

class TacMediaManager {

  /**
   * Render field OEmbed field from media object
   *
   * @param \Drupal\media\Entity\Media $media
   * @param string $fieldName
   *
   * @return array
   */
  public function buildMediaOEmendedFieldDisplay(Media $media, string $fieldName) {
    try {
      /* @var StringItem $field */
      $field = $media->get($fieldName);

      return $field[0]->view(['type' => 'oembed']);
    }
    catch(InvalidArgumentException $exception) {
      return [];
    }
  }

}
