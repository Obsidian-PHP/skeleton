<?php
namespace App\Domain\Auth;

use Core\Http\Register;
use Core\Repository;
use Illuminate\Support\Collection;

#[Register('userRepository', UserRepository::class)]
class UserRepository extends Repository {
    public function getAll(): Collection
    {
        return $this->table('users')
            ->get();
    }

    public function get(int $id): Collection
    {
        return $this->table('users')
            ->where(['id' => $id])
            ->get();
    }

    public function getByEmail(string $email): object|null
    {
        return $this->table('users')
            ->where('email', $email)
            ->first(['username', 'email', 'role', 'password', 'status', 'login_attempt', 'last_login']);
    }

    public function getByUsername(string $username): object|null
    {
        return $this->table('users')
            ->where('username', $username)
            ->first(['username', 'email', 'role', 'password', 'status', 'login_attempt', 'last_login']);
    }
}