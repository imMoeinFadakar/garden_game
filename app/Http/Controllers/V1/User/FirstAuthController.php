<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Auth\FirstAuthRequest;
use App\Http\Requests\V1\User\Auth\SecondStepAuth;
use App\Http\Resources\V1\Admin\RegisterResource;
use App\Http\Resources\V1\User\AuthResource;
use App\Models\User;
use App\Models\UserAvatar;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class FirstAuthController extends BaseUserController
{

    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new  User();
    }

    /**
     * first login/post 
     * @param \App\Http\Requests\V1\User\Auth\FirstAuthRequest $request
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function userLogin(FirstAuthRequest $request,User $user)
    {   

        $findOrNewUser =  $user->findOrNewUser($request->validated()); // find or create user

        $this->loginUser($findOrNewUser); // login user
        
      
        $token = $this->createUserToken($findOrNewUser); // user token 
          
        return $this->api(new AuthResource(['user'=>$findOrNewUser,"token"=>$token]),__METHOD__);

        
    }
    /**
     * Summary of createUserToken
     * @param \Illuminate\Auth\Authenticatable $user
     * @return string (Token)
     */
    public function createUserToken(Authenticatable $user): string
    {
        return $user->createToken("USER TOKEN",[null],Carbon::now()->addHours(6))->plainTextToken;
    }

    /**
     * login user
     * @param mixed $user
     */
    public function loginUser($user)
    {
       return  Auth::login($user);
    }

    /**
     * Find user or make new one 
     * @param mixed $request
     * @return User
     */
    public function findOrNewUser($request)
    {
        return User::query()
            ->firstOrCreate(["name"=>$request->name,"telegram_id"=>$request->telegram_id]);
    }

}
