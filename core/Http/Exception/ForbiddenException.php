<?php

declare(strict_types=1);

namespace Core\Http\Exception;

use Core\Exception\HTTPException;

/**
 * Represents an HTTP 403 Forbidden exception.
 *
 * This exception is thrown when the user is not authorized to access the requested resource.
 * It extends the `HTTPException` class and sets the appropriate HTTP status code and error message.
 */
final class ForbiddenException extends HTTPException
{
    /**
     * The HTTP status code for this exception.
     *
     * @var int
     */
    protected $code = 403;

    /**
     * The error message for this exception.
     *
     * @var string
     */
    protected $message = 'Oops! 😖 You are not logged';
}
