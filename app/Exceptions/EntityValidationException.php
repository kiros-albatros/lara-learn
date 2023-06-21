<?php

namespace App\Exceptions;

use Exception;

class EntityValidationException extends Exception
{
    public function __construct(
        /** @var array[] $messages */
        public readonly array $messages = []
    ) {
        // Заполняем стандартное сообщение для красивого вывода во всех возможных обертках
        $this->message = 'Validation errors:';
        foreach ($messages as $key => $errors) {
            $this->message .= ' ' . $key . ' (' . implode(' / ', $errors) . ')';
        }
    }
}
