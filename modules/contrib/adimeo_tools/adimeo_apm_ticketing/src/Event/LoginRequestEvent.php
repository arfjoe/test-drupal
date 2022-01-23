<?php


namespace Drupal\adimeo_apm_ticketing\Event;


use Drupal\user\UserInterface;

class LoginRequestEvent extends \Symfony\Component\EventDispatcher\Event
{

  /**
   * @var UserInterface
   */
  private $account;

  public function __construct(UserInterface $account)
  {

    $this->account = $account;
  }

  /**
   * Get connected user account
   *
   * @return UserInterface
   */
  public function getAccount()
  {
    return $this->account;
  }

}
