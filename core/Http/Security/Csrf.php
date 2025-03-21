<?php

namespace Core\Http\Security;

use Core\Http\Service\Container;

class Csrf
{
    private const TOKEN_LIMIT = 5;

    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(16));

        $tokens = Container::get()->session->get('csrf', []);
        if (!is_array($tokens)) {
            $tokens = [];
        }

        $tokens[] = $token;
        if (count($tokens) > self::TOKEN_LIMIT) {
            array_shift($tokens);
        }

        Container::get()->session->set('csrf', $tokens);
        return $token;
    }

    public function getToken(): string
    {
        $tokens = Container::get()->session->get('csrf', []);
        if (!is_array($tokens) || empty($tokens)) {
            return $this->generateToken();
        }
        return end($tokens);
    }

    public function isValid(string $token): bool
    {
        $tokens = Container::get()->session->get('csrf');
        if (!is_array($tokens)) {
            return false;
        }
        if (in_array($token, $tokens, true)) {
            register_shutdown_function(function () use ($token, $tokens) {
                $tokens = array_diff($tokens, [$token]);
                Container::get()->session->set('csrf', $tokens);
            });
            return true;
        }
        return false;
    }

    public function removeToken(): void
    {
        Container::get()->session->delete('csrf');
    }

    public static function render(): string
    {
        return sprintf('<input type="hidden" name="csrf" value="%s">', Container::get()->csrf->getToken());
    }
}