<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Auth\FirstAuthRequest;
use App\Http\Requests\V1\User\Auth\SecondStepAuth;
use App\Http\Resources\V1\Admin\RegisterResource;
use App\Models\User;
use App\Models\UserAvatar;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class FirstAuthController extends BaseUserController
{

    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new  User();
    }


    public function firstStepLogin(FirstAuthRequest $request,Wallet $wallet)
    {

        $user = $this->findOrNewUser($request);

        $this->loginUser($user);

        $token = $user->createUserAccessToken();
        $wallet->createNewWallet();

        return $this->api(new RegisterResource(["user" => $user,"token"=>$token]),__METHOD__);
    }

    public function createNewWallet()
    {
        $userWallet = $this->hasUserWallet();
        if(! $userWallet){
            return Wallet::query()->create(["user_id"=>1]);
        }
        return true;
    }

    public function hasUserWallet(): bool
    {
        $userWallet = Wallet::query()->where("user_id",1)->first(); // add Auth::id()
        if(! $userWallet)
            return false;


        return true;
    }


    public function loginUser($user)
    {
        Auth::login($user);
    }

    public function findOrNewUser($request)
    {
        return User::query()
            ->firstOrCreate(["name"=>$request->name,"telegram_id"=>$request->telegram_id]);
    }


    public function secondStepLogin(SecondStepAuth $request)
    {


        $user = User::addUsername($request->username);

        $avatar = UserAvatar::query(["user_id" => Auth::id(),"avatar_id" => $request]);

        return $this->api(new RegisterResource($user->toArray()),__METHOD__);



    }



}
