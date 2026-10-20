<?php

namespace App\Policies;

use App\Models\User;

class ImportPolicy
{
    public function getImport(User $user, $import): bool
    {
        return $user->id === $import->user_id;
    }
}
