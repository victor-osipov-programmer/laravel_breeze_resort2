<?php

namespace App\Exceptions;

use Exception;

class NotFound extends GeneralError
{
    function __construct($message = 'Not Found')
    {
        parent::__construct($message, 404);
    }
}
