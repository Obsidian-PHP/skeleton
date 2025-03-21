<?php

namespace Core\Session;

class SessionManager
{
    public static function set(string $identifier, mixed $value): void
    {
        $_SESSION[$identifier] = $value;
    }

    public static function get(string $identifier, mixed $default = null): mixed
    {
        return $_SESSION[$identifier] ?? $default;
    }

    public static function exists(string $identifier): bool
    {
        return isset($_SESSION[$identifier]);
    }

    public static function delete(string $identifier): void
    {

        unset($_SESSION[$identifier]);
    }

    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }
}
