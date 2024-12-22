<?php

declare(strict_types = 1);

namespace Core\Exception;

use Exception;

abstract class HTTPException extends Exception 
{
    public function label(): string
    {
        return match ($this->code) {
            403 => 'Forbidden',
            404 => 'Page Not Found',
            405 => 'Method Not Allowed',
            501 => 'Not Implemented'
        };
    }

    public function parameters(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'label' => $this->label()
        ];
    }
}