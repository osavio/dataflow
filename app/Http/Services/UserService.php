<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;
use App\Models\User;

class UserService
{
    public function create($data): User
    {
        $user = null;

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            
            return $user;
        } catch(Throwable $e) {
            Log::error('Failed to create user.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw new RuntimeException('It was not possible to create the user.', 0, $e);
        }
    }
}
