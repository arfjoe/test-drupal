<?php

namespace Drupal\adimeo_apm_tracking\Manager;

use Drupal\adimeo_apm_tracking\Manager\Interfaces\TrackingProcessingInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Render\Renderer;

class MailManager implements TrackingProcessingInterface
{
  /**
   * @var MailManagerInterface
   */
  private $mailManager;
  /**
   * @var Renderer
   */
  private $renderer;


  private static function SITE_NAME()
  {
    return \Drupal::config('system.site')->get('name');
  }

  private static function LANGCODE()
  {
    return \Drupal::config('system.site')->get('langcode');
  }

  const MODULE = 'adimeo_apm_tracking';
  const KEY = 'adimeo_apm_tracking_mail';
  const TO = 'nicofabing@gmail.com';
  const REPLY = null;
  const SEND = TRUE;

  public function __construct(MailManagerInterface $mailManager, Renderer $renderer)
  {

    $this->mailManager = $mailManager;
    $this->renderer = $renderer;
  }

  private function prepareMail($message)
  {
    // defining email parameters
    $render = [
      '#theme' => 'apm_tracking_mail',
      '#message' => $message,
    ];

    $params['message'] = $this->renderer->renderRoot($render);
    $params['subject'] = t('Daily tracking for : @sitename', array('@sitename' => self::SITE_NAME()));
    $params['options']['username'] = 'Admin';

    return $params;
  }

  private function sendMail(array $params)
  {
    $result = $this->mailManager->mail(self::MODULE, self::KEY, self::TO,
      self::LANGCODE(), $params, self::REPLY, self::SEND);

    // check if email was send and log the result
    if ($result['result'] == true) {
      \Drupal::logger('tracking_cron')->info('Daily tracking email was sent successfully');
    } else {
      \Drupal::logger('tracking_cron')->info('Daily tracking email could not be send');
    }


  }


  public function send(array $data)
  {

    $mailParams = $this->prepareMail($data);
    $this->sendMail($mailParams);


  }


}
