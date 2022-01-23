<?php

namespace Drupal\adimeo_apm_ticketing\EventSubscriber;

use Drupal\adimeo_apm_ticketing\Event\LoginEvents;
use Drupal\adimeo_apm_ticketing\Event\LoginRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LoginSubscriber.
 */
class LoginSubscriber implements EventSubscriberInterface
{
  /**
   * Time before the cookie expire in seconds
   */
  const EXPIRE_TIME = 86400; // 1 day in seconds

  /**
   * Value of the user-is-rapporteur-apm cookie
   */
  const COOKIE_VALUE = 'true';

  public function onLoginRequest(LoginRequestEvent $event)
  {
    $account = $event->getAccount();

    if (!empty($_COOKIE['user-is-rapporteur-apm'])) {

      return false;

    } else if (!$account->hasRole('rapporteur_apm')) {

      return false;

    }

    \Drupal::logger('adimeo_apm_ticketing')->info('Created "user-is-rapporteur-apm" cookie for : ' . $account->getAccountName());
    setcookie('user-is-rapporteur-apm', static::COOKIE_VALUE, time() + LoginSubscriber::EXPIRE_TIME, '/');

    return true;

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents()
  {

    return [
      LoginEvents::NEW_LOGIN_REQUEST => 'onLoginRequest',
    ];
  }

}
