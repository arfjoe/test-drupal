<?php

namespace Drupal\adimeo_apm_tracking\Controller;

use Drupal\adimeo_apm_tracking\Form\ConfigForm;
use Drupal\adimeo_apm_tracking\Manager\TrackingManager;
use Drupal\adimeo_apm_tracking\Exception\NoApiKeyHeaderException;
use Drupal\adimeo_apm_tracking\Exception\WrongApiKeyHeaderException;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StatusReportController extends ControllerBase {

  const API_KEY_HEADER = 'adimeo-apm-tracking-api-key';

  protected TrackingManager $trackingManager;

  protected string $allowedApiKey;

  public function __construct(TrackingManager $trackingManager, ConfigFactoryInterface $configFactory) {
    $this->trackingManager = $trackingManager;
    $this->allowedApiKey = $configFactory->get(ConfigForm::CONFIG_KEY)->get(ConfigForm::API_KEY_PARAM);
  }

  public static function create(ContainerInterface $container) {
    /** @var TrackingManager $trackingManager */
    $trackingManager = $container->get('adimeo_apm_tracking.processing.manager');
    $configFactory = $container->get('config.factory');
    return new static($trackingManager, $configFactory);
  }

  public function getStatusReport(Request $request) {
    $httpHeaders = $request->headers;

    if (!$httpHeaders->has(self::API_KEY_HEADER)) {
      throw new NoApiKeyHeaderException();
    }

    $requestApiKey = (string) $httpHeaders->get(self::API_KEY_HEADER);
    if ($requestApiKey !== $this->allowedApiKey) {
      throw new WrongApiKeyHeaderException();
    }
    
    $data = $this->trackingManager->fetchData();
    return new JsonResponse((array) $data);
  }
}