<?php

namespace App\Domain\Auth;

use App\Domain\Auth\Event\LoginFailureEvent;
use App\Domain\Auth\Event\LoginSuccessEvent;
use Core\Http\Request;
use Core\Http\Service\Container;
use Core\Http\Register;

#[Register('authService', AuthService::class)]
class AuthService extends UserRepository 
{
    public function authenticate(Request $_resquest): bool
    {
        $email = (string) $_resquest->getBody()['email'];
        $password = (string) $_resquest->getBody()['password'];
        $user =  $this->getByEmail($email);

        if (!$_resquest->getBody()['csrf'] === Container::get()->csrf->getToken())
        {
            Container::get()->flash->error('Incorect csrf token !');
            return false;
        }

        if (!$user)
        {
            Container::get()->flash->error('Sorry this account does not exist');
            return false;
        }

        if (!$user->status >= 1)
        {
            Container::get()->flash->error('Oops! 😖 Ce compte a été bloqué');
            return false;
        }

        if (!password_verify($password, $user->password))
        {
            Container::get()->flash->error('Sorry incorect password !');
            Container::get()->dispatcher->dispatch(new LoginFailureEvent($user));
            return false;
        }

        Container::get()->session->set('user', $user);
        Container::get()->dispatcher->dispatch(new LoginSuccessEvent($user));
        Container::get()->flash->success('Login successfull !');
        return true;
    }

    public function signout(): bool
    {
        Container::get()->session->delete('user');
        Container::get()->flash->success('Logout successfull !');
        return true;
    }
}
