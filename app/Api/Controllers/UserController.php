<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\UserInfoRequest;

class UserController extends Controller
{
    public function store(UserInfoRequest $request)
    {
        $user = auth()->user();
        $user->birthday = $request->get('birthday');
        $user->phone = $request->get('phone');
        $user->save();

        return api()->item($user, UserResource::class);
    }

    public function show() {
        $user = auth()->user();

        return api()->item($user, UserResource::class);
    }
}