<?php

namespace App\Domain\Auth\Event;

use Core\Http\Listener\Event;
use Core\Http\Service\Container;
use Core\Http\User\UserInterface;

class LoginSuccessEvent extends Event
{
    /**
     * @var object
     */
    private $object;

    /**
     * PreCreateEvent constructor.
     * @param object $object
     */
    public function __construct(object $object)
    {
        $this->object = $object;
    }

    public function getUser(): UserInterface
    {
        return Container::get()->loggedUser;
    }

    public function getContainer(): Container
    {
        return Container::get();
    }
}
