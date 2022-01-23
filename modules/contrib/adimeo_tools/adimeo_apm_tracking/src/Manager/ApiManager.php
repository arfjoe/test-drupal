<?php


namespace Drupal\adimeo_apm_tracking\Manager;

use Drupal\adimeo_apm_tracking\Manager\Interfaces\TrackingProcessingInterface;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\Client;


class ApiManager implements TrackingProcessingInterface
{

  // TODO : API END POINT
  const API_URL = '';

  /**
   * @var Json
   */
  private $serializer;
  /**
   * @var Client
   */
  private $client;

  public function __construct(Json $serializer, Client $httpClient)
  {
    $this->serializer = $serializer;
    $this->client = $httpClient;
  }


  public function send(array $data)
  {
    $config = \Drupal::config('apm_tracking.config');
    $jsonData = $this->serializer->encode($data);
    $request = $this->client->post(self::API_URL, [
      'json' => [
        $jsonData
      ],
      'config' => [
        'site_environnement' => $config->get('apm_tracking_environnement'),
        'site_id' => $config->get('apm_tracking_id')
      ]
    ]);

    $response = $request->getBody();

  }
}
