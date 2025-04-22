<?php

namespace App\Http\Controllers\V1\User;


use App\Http\Requests\V1\User\UserRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(auth()->id());

        return $this->api(new UserResource($user->toArray()),__METHOD__);
        
    }

    
    public function findingUser(UserRequest $request)
    {
        $findUser = User::query()
        ->where("telegram_id",$request->telegram_id)
        ->first();

        if(! $findUser)
            return $this->api(null,__METHOD__,"user is not exists");


        return $this->api(new UserResource($findUser->toArray()),__METHOD__);

    }



}
