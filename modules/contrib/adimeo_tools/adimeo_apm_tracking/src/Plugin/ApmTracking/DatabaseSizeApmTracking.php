<?php

namespace Drupal\adimeo_apm_tracking\Plugin\ApmTracking;

use Drupal\adimeo_apm_tracking\Annotation\ApmTracking;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingBase;
use Drupal\adimeo_apm_tracking\Plugin\ApmTrackingInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  Database size infos
 *
 * @ApmTracking(
 *  id = "site_database_size",
 *  label = "Site database size"
 * )
 */
class DatabaseSizeApmTracking extends ApmTrackingBase implements ApmTrackingInterface, ContainerFactoryPluginInterface
{

  /**
   * @var Connection
   */
  private $database;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $connection)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $connection;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }

    /**
     * @inheritDoc
     */
    public function fetch()
    {
      $dbinfos = $this->database->getConnectionOptions();
      $dbName = $dbinfos['database'];

      $query = $this->database->query('SELECT SUM(data_length + index_length) / 1024 / 1024
        AS "size"
        FROM information_schema.TABLES
        WHERE table_schema = :drupalDbName
        GROUP BY table_schema',
        [':drupalDbName' => $dbName]);

      $result = $query->fetch(\PDO::FETCH_ASSOC);

      return $result;
    }


}
