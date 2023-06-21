<?php

namespace App\Exceptions;

use Exception;

class EntityNotDeletedException extends Exception
{
    public function __construct(
        public $message = null
    ) {
    }
}
