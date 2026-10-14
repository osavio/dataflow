<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->create($request->validated());
        
        return new UserResource($user);
    }
}
