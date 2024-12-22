<?php

namespace Core\Session;

class SessionManager
{
    public static function set($identifier, $value)
    {
        $_SESSION[$identifier] = $value;
    }

    public static function get($identifier)
    {
        return (isset($_SESSION[$identifier]))? $_SESSION[$identifier] : '';
    }

    public static function exists($identifier)
    {
        return isset($_SESSION[$identifier]);
    }

    public static function delete($identifier)
    {
        unset($_SESSION[$identifier]);
    }
}