<?php

namespace App\Auth;

use App\Models\User;
use Framework\Auth\SessionAuthenticator;
use Framework\Core\IIdentity;

class DbAuthenticator extends SessionAuthenticator
{
    protected function authenticate(string $username, string $password): ?IIdentity
    {
        try {
            $users = User::getAll('email = ?', [$username]);
            if (!empty($users)) {
                $user = $users[0];
                if ($user->verifyPassword($password)) {
                    return $user;
                }
            }
        } catch (\Exception $e) {
            // Log error or ignore
        }
        return null;
    }
}
