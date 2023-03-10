<?php

namespace Riyu\Helpers\Errors;

use Exception;
use Riyu\App\Config;
use Riyu\Helpers\Errors\Backtrace\Handler\Backtrace;
use Throwable;

class AppException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    final public static function msg()
    {
        return self::$message;
    }

    final public function code()
    {
        return $this->code;
    }
}
