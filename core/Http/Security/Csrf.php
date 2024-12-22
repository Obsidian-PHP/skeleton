<?php

namespace Core\Http\Security;

use Core\Http\Service\Container;

class Csrf
{
    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(16));
        Container::get()->session->set('csrf', $token);
        return $token;
    }

    public function removeToken(): void
    {
        Container::get()->session->delete('csrf');
    }

    public function getToken(): string
    {
        return Container::get()->session->get('csrf');
    }

    public static function render(): string
    {
        return sprintf('<input type="hidden" name="csrf" value="%s">', Container::get()->csrf->getToken());
    }
}