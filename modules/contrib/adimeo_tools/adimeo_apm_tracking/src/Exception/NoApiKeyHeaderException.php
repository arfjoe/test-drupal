<?php

namespace Drupal\adimeo_apm_tracking\Exception;

use Exception;
use Throwable;

class NoApiKeyHeaderException extends Exception {

  const MESSAGE = "Missing header API KEY from Request.";

  public function __construct($message = "", $code = 0, Throwable $previous = NULL) {

    parent::__construct(self::MESSAGE, $code, $previous);
  }

}
