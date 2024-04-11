<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends DefaultRepository
{
    public function find(int $userId, bool $rewrite = false): ?User
    {
        return $this->lazy(fn() => User::find($userId), json_encode($userId), $rewrite);
    }

    public function updateUsers(array $ids): void
    {
        foreach ($ids as $id) {
            $this->update(fn() => User::find($id), json_encode($id));
        }
    }
}
