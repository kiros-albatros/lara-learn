<?php

namespace App\Exceptions;

use Exception;

class EntityNotUpdatedException extends Exception
{
    public function __construct(
        public $message = null
    ) {
    }
}
