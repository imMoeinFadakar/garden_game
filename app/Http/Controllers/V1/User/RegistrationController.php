<?php

namespace App\Http\Controllers\V1\User;

use Auth;
use App\Models\User;
use App\Http\Resources\UserRegistrationResource;
use App\Http\Requests\V1\User\Auth\SecondStepAuth;
use App\Http\Requests\V1\User\RegistrationRequest;
use App\Http\Resources\V1\User\RegistrationResource;

class RegistrationController extends BaseUserController
{
    public function store(SecondStepAuth $request, User $user)
    {
        $user = $user->addnewUser($request);
        return $this->api(new UserRegistrationResource($user->toArray()),__METHOD__);
    }


    /**
     * add user name and gender to user table
     * @param \App\Http\Requests\V1\User\RegistrationRequest $request
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function addUsernameAndGender(RegistrationRequest $request,User $user)
    {


        $validated = $request->validated();

        $user = auth()->user();
        $user->gender = $validated["gender"];
        $user->username = $validated["username"];
        $user->save();


        $user->id = null;

        return $this->api(new RegistrationResource($user),__METHOD__);
    }

}
