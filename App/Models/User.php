<?php

namespace App\Models;

use Framework\Core\IIdentity;
use Framework\Core\Model;

/**
 * Class User
 * @package App\Models
 */
class User extends Model implements IIdentity
{
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $role; // 'admin' or 'agent'

    protected static ?string $tableName = 'users';

    public function verifyPassword(string $password): bool
    {
        // For development/seeder compatibility, if password starts with '$2y$', verify hash.
        // Otherwise checks plain text (legacy/fallback).
        if (str_starts_with($this->password, '$2y$')) {
            return password_verify($password, $this->password);
        }
        return $this->password === $password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
