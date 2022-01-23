<?php

namespace Drupal\adimeo_apm_tracking\Plugin\ApmTracking;

use Drupal\adimeo_apm_tracking\Annotation\ApmTracking;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingBase;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\system\SystemManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  Dashboard errors infos
 *
 * @ApmTracking(
 *  id = "site_dashboard_errors",
 *  label = "Site dashboard errors"
 * )
 */
class DashboardErrorsApmTracking extends ApmTrackingBase implements ApmTrackingInterface, ContainerFactoryPluginInterface
{

  /**
   * @var SystemManager
   */
  private $systemManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, SystemManager $systemManager)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);


    $this->systemManager = $systemManager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('system.manager')
    );
  }


  /**
   * @inheritDoc
   */
  public function fetch()
  {
    $requirements = $this->systemManager->listRequirements();

    $errors = array();
    // loop through to find errors
    foreach ($requirements as $requirement) {
      if (!empty($requirement['severity']) && $requirement['severity'] === 2) {
        $errors[] = $requirement;
      }
    }

    return array_map(function ($errorData) {
      $description = $errorData['description'];

      if (is_array($errorData['description'])) {
        if (isset(reset($errorData['description'])['#markup'])) {
          $description = reset($errorData['description'])['#markup'];
        }
        elseif (isset($errorData['description']['#context']) && isset($errorData['description']['#context']['error'])) {
          $description = $errorData['description']['#context']['error'];
        }
        else {
          return false;
        }
      }

      return $errorData['title'] . ' : ' . $description;
    }, $errors);
  }


}
