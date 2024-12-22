<?php

namespace Core\Http\Security\Voter;

use Core\Http\User\UserInterface;

interface VoterInterface {
    
    public function supports(string $permssion, mixed $subject = null): bool;

    public function voteOnAttribute(UserInterface $user, string $permission, $subject = null): bool;
}