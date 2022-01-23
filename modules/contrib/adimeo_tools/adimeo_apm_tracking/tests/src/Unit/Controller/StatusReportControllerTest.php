<?php

namespace Drupal\Tests\adimeo_apm_tracking\Unit\Controller;

use Drupal\adimeo_apm_tracking\Controller\StatusReportController;
use Drupal\adimeo_apm_tracking\Form\ConfigForm;
use Drupal\adimeo_apm_tracking\Manager\TrackingManager;
use Drupal\adimeo_apm_tracking\Exception\NoApiKeyHeaderException;
use Drupal\adimeo_apm_tracking\Exception\WrongApiKeyHeaderException;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \Drupal\adimeo_apm_tracking\Controller\StatusReportController
 * @group adimeo_apm_tracking
 * @group adimeo_tools
 * @todo add all intended behaviors in tests
 * @author adimeo
 */
class StatusReportControllerTest extends UnitTestCase {

  const MOCK_ALLOWED_API_KEY = 'fake-api-key';

  const FORBIDDEN_API_KEY = 'not-the-same-api-key';

  const MOCK_CONFIG = [
    ConfigForm::CONFIG_KEY => [
      ConfigForm::API_KEY_PARAM => self::MOCK_ALLOWED_API_KEY,
    ],
  ];

  protected StatusReportController $statusReportController;

  protected function setUp(): void {
    $trackingManager = $this->createMock(TrackingManager::class);
    $configFactory = $this->getConfigFactoryStub(self::MOCK_CONFIG);
    $this->statusReportController = new StatusReportController($trackingManager, $configFactory);
    parent::setUp();
  }

  /**
   *  Test reaction to a missing payload from an API
   */
  public function testCheckHeaderApiKeyExistence() {
    $request = new Request();

    $this->expectException(NoApiKeyHeaderException::class);
    $this->statusReportController->getStatusReport($request);
  }

  public function testErrorOnBadApiKey() {
    $request = new Request();
    $request->headers->add([StatusReportController::API_KEY_HEADER => self::FORBIDDEN_API_KEY]);

    $this->expectException(WrongApiKeyHeaderException::class);
    $this->statusReportController->getStatusReport($request);
  }

  public function testResponseOnGoodApiKey() {
    $request = new Request();
    $request->headers->add([StatusReportController::API_KEY_HEADER => self::MOCK_ALLOWED_API_KEY]);

    $response = $this->statusReportController->getStatusReport($request);
    $this->assertInstanceOf(JsonResponse::class, $response);
  }

}