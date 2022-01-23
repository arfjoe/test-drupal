<?php


namespace Drupal\adimeo_apm_tracking\Manager;


class SendingManager
{

  /**
   * @var ApiManager
   */
  private $apiManager;
  /**
   * @var MailManager
   */
  private $mailManager;

  public function __construct(ApiManager $apiManager, MailManager $mailManager)
  {

    $this->apiManager = $apiManager;
    $this->mailManager = $mailManager;
  }

  public function sendEmail(array $data)
  {
    $this->mailManager->send($data);
  }

  public function sendApi(array $data)
  {
    $this->apiManager->send($data);
  }

}
