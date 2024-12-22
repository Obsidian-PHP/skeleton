<?php

namespace Core\Http\User;

interface UserInterface {
    
    public function getRoles(): string;

    public function getUser(): mixed;

    public function getUserIdentifier(): string;
}