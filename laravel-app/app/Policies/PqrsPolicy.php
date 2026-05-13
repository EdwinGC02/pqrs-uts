<?php

namespace App\Policies;

use App\Models\Pqrs;
use App\Models\User;

class PqrsPolicy
{
    public function view(User $user, Pqrs $pqrs): bool
    {
        return $user->isAdmin() || $user->id === $pqrs->user_id;
    }
}
