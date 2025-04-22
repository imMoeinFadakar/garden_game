<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\v1\User\FindUserRequest;
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

        return $this->api(new UserResource(auth()->user()),__METHOD__);
        
    }

    
    public function findingUser(FindUserRequest $request)
    {
        $findUser = User::query()
        ->where("telegram_id",$request->telegram_id)
        ->first();

        if(! $findUser)
            return $this->api(null,__METHOD__,"user is not exists");


        return $this->api(new UserResource($findUser->toArray()),__METHOD__);

    }



}
