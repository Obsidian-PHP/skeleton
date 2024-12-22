<?php

namespace Core\Http\User;

use Core\Http\Service\Container;

class LoggedUser implements UserInterface {

    public function getRoles(): string
    {
        if ($this->isLogged())
        {
            return Container::get()->session->get('user')->role;
        }
        return 'visitor';
    }

    public function getUser(): mixed
    {
        if ($this->isLogged())
        {
            return Container::get()->session->get('user');
        }
        return [];
    }

    public function getUserIdentifier(): string
    {
        if ($this->isLogged())
        {
            return Container::get()->session->get('user')->username;
        }
        return 'visitor';
    }
    
    public function isLogged(): bool
    {
        if (isset(Container::get()->session->get('user')->username)) { return true; }
        return false;
    }
}