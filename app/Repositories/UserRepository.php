<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends DefaultRepository
{
    public function find(int $userId): ?User
    {
        return $this->lazy(fn() => User::find(1), json_encode($userId));
    }
}
