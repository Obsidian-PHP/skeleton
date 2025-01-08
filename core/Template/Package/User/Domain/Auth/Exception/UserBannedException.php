<?php

namespace App\Domain\Auth\Exception;

use Core\Exception\HTTPException;

final class UserBannedException extends HTTPException
{
    protected $code = 403;

    protected $message = 'Oops! 😖 Ce compte a été bloqué';
}
