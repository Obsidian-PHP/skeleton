<?php

namespace Core\Http\Security\Voter;

use Core\Http\User\UserInterface;

final class Permission {
    
    /**
     * @var VoterInterface[]
     */
    private array $voters = [];

    public function can(UserInterface $user, string $permission, $subject = null): bool
    {
        foreach($this->voters as $voter) {
            if($voter->supports($permission, $subject)) {
                $vote = $voter->voteOnAttribute($user, $permission, $subject);

                if ($vote === true) {
                    return true;
                }
            }
        }
        return false;
    }

    public function addVoter(VoterInterface $voter): void
    {
        $this->voters[] = $voter;
    }
}