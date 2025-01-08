<?php

namespace App\Domain\Auth;

use Core\Http\Service\Service;
use Core\Http\Register;

#[Register('userService', UserService::class)]
class UserService extends Service
{
    private string $username;
    
    public function interact(string $username): void
    {
        $this->username = $username;
    }

    public function update(array $data)
    {
        return $this->table('users')
            ->where(['username' => $this->username])
            ->update($data);
    }
}