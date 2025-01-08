<?php

namespace App\Domain\Auth\Exception;

use Core\Exception\HTTPException;

final class IncorectPasswordException extends HTTPException
{
    protected $code = 403;

    protected $message = 'Oops! 😖 Incorrect password';
}
