<?php

namespace Core\Http\Security\voter;

use Core\Http\Security\Voter\Permission;
use Core\Http\Service\Container;
use Core\Http\User\LoggedUser;

class RegisterVoter
{
    public static function authorizationChecker(mixed $attribute, mixed $subject = null): bool
    {
        $permission = new Permission();
        $user = new LoggedUser();

        Container::get()->registerVoter($permission);
        
        $req = $permission->can($user, $attribute, $subject);
        return $req;
    }
}