<?php

namespace Drupal\adimeo_tools\Drush;

/**
 * Trait AdimeoToolsCommandsTrait.
 *
 * Ce trait permet de gérer l'unicité de fonctionnement des commande drush 9 et drush 8.
 *
 * @package Drupal\adimeo_tools\Drush
 */
trait AdimeoToolsCommandsTrait {

  /**
   * Modifie la version d'un module : Ex: drush smv mon_module 8003.
   *
   * @param string $moduleName
   *   Module name.
   * @param string $version
   *   Version id.
   *
   * @command set_module_version
   *
   * @aliases smv
   */
  public function setModuleVersion($moduleName = NULL, $version = NULL) {
    $doAction = TRUE;
    if (empty($moduleName)) {
      drush_log('Veuillez indiquer le nom du module', 'error');
      $doAction = FALSE;
    }
    if (empty($version)) {
      drush_log('Veuillez indiquer la version à appliquer', 'error');
      $doAction = FALSE;
    }

    if ($doAction) {
      \Drupal::keyValue('system.schema')->set($moduleName, $version);
      $version = \Drupal::keyValue('system.schema')->get($moduleName);
      drush_log(t('Le module @module est désormais en version @version', [
        '@module'  => $moduleName,
        '@version' => $version
      ])->__toString(), 'ok');
    }
    else {
      drush_log('Ex: drush smv mon_module 8003', 'ok');
    }
  }

  /**
   * Recharge la config par défault d'un module. (très pratique pour les migrations)
   *
   * @param string $module
   *   Nom du module.
   *
   * @command reload-module-config
   *
   * @aliases rmc
   */
  public function reloadModuleConfig($module) {
    \Drupal::service('config.installer')
      ->installDefaultConfig('module', $module);
    drush_log(t('Install default config of @module has set.', ['@module' => $module]), 'ok');
  }

  /**
   * Executes exif rotation on images (file entity) through module exif_orientation method
   *
   *
   * @command images-apply-exif-rotation
   *
   * @aliases iaer
   */
  public function imagesApplyExifRotation() {

    if (!extension_loaded('exif')) {
      drush_log(t('Cannot execute drush command, php-exif extension is not loaded for PHP CLI.'),'error' );
      var_dump('Cannot execute drush command, php-exif extension is not loaded for PHP CLI.');
      return;
    }

    $moduleHandler = \Drupal::service('module_handler');
    if (!$moduleHandler->moduleExists('exif_orientation')) {
      drush_log(t('Cannot execute drush command, exif_orientation drupal module is not enabled.'),'error' );
      var_dump('Cannot execute drush command, exif_orientation drupal module is not enabled.');
      return;
    }

    // charge images
    $fids = \Drupal::entityQuery('file')
      ->sort('fid', 'ASC')
      ->condition('filemime', 'image/jpeg')
      ->execute();

    // put in queue
    $queueFactory = \Drupal::service('queue');
    $queue = $queueFactory->get('images_apply_exif_rotation');
    foreach($fids as $fid) {
      $queue->createItem($fid);
    }
  }
}
