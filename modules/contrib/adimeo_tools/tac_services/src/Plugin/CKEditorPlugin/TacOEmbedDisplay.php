<?php

namespace Drupal\tac_services\Plugin\CKEditorPlugin;

use Drupal\ckeditor\Annotation\CKEditorPlugin;
use Drupal\Core\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\ckeditor\CKEditorPluginCssInterface;

/**
 * Defines the "TacOEmbedDisplay" plugin.
 *
 * @CKEditorPlugin(
 *   id = "tacoembeddisplay",
 *   label = @Translation("Tac OEmbed display plugin"),
 *   module = "ckeditor"
 * )
 */
class TacOEmbedDisplay extends PluginBase implements CKEditorPluginContextualInterface {

  /**
   * {@inheritdoc}
   */
  public function isInternal() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'tac_services') . '/libraries/ck_plugins/oembed/plugin.js';
  }


  /**
   * {@inheritdoc}
   */
  public function isEnabled(Editor $editor) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor)
  {
    return [];
  }

  public function getLibraries(Editor $editor)
  {
    return [];
  }
}
