<?php

namespace Core\Http\Security\Voter;

use application\core\http\users\LoggedUser;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class IsGranted {

    public function __construct(private string $permission)
    {
     /**
      * Code
      */
    }

    public function getPermission(): string
    {
        return $this->permission;
    }
}   