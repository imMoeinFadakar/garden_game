<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\Auth\RegistrationRequest;
use App\Http\Resources\UserRegistrationResource;
use App\Models\User;

class RegistrationController extends BaseUserController
{
    public function store(RegistrationRequest $request,User $user)
    {
        $user = $user->addnewUser($request);
        return $this->api(new UserRegistrationResource($user->toArray()),__METHOD__);
    }
}
