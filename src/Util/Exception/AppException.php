<?php

namespace App\Util\Exception;

use RuntimeException;

class AppException extends RuntimeException
{
    public function __construct($message, $code = 200, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
