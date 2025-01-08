<?php

namespace App\Domain\Auth\Event;

use Core\Http\Listener\Event;

class UserBannedEvent extends Event
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

    /**
     * @return object
     */
    public function getObject(): object
    {
        return $this->object;
    }
}
