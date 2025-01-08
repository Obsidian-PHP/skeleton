<?php

namespace App\Domain\Auth\Event;

use Core\Http\Listener\Event;
use Core\Http\Service\Container;

class LoginFailureEvent extends Event
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

    public function getUser(): Object
    {
        return Container::get()
            ->userRepository
            ->getByEmail($this->object->email);
    }
}
