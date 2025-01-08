<?php

namespace App\Domain\Auth\Listener;

use App\Domain\Auth\Event\LoginFailureEvent;
use App\Domain\Auth\Event\LoginSuccessEvent;
use Core\Http\Service\Container;
use Core\Http\User\UserInterface;

class LoginListener
{
    public function __invoke($event): void
    {
        if ($event instanceof LoginSuccessEvent) { $this->onLoginSuccess($event); }
        if ($event instanceof LoginFailureEvent) { $this->onLoginFailure($event); }
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof UserInterface)
        {
            Container::get()->userService->interact($user->getUserIdentifier());
            Container::get()->userService->update(['last_login' => new \DateTimeImmutable(), 'login_attempt' => 0]);
            Container::get()->csrf->removeToken();
        }
    }
    
    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof \stdClass)
        {
            Container::get()->userService->interact($user->username);

            if ($user->login_attempt >= 3)
            {
                Container::get()->userService->update(['status' => 0]);
            } else {
                Container::get()->userService->update(['login_attempt' => $user->login_attempt+1]);
            }
        }
    }
}