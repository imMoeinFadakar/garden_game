<?php

namespace App\Http\Controllers\V1\User;


use App\Http\Requests\V1\User\Auth\FirstAuthRequest;
use App\Http\Resources\V1\User\AuthResource;
use App\Models\User;
use App\Services\AuthService;

class FirstAuthController extends BaseUserController
{


    protected AuthService $authService;

    // /**
    //  * Class constructor.
    //  */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * first login/post
     * @param \App\Http\Requests\V1\User\Auth\FirstAuthRequest $request
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function loginUserByTelegramId(FirstAuthRequest $request,User $user)
    {

        $findOrNewUser =  $this->authService->findOrCreateUser($request->validated()); // find or create user

        $this->authService->loginUser($findOrNewUser); // login user

        $token = $this->authService->createAccessToken($findOrNewUser); // user token

        $findOrNewUser->id =null;

        return $this->api(
            new AuthResource(['user'=>$findOrNewUser,"token"=>$token])
            ,__METHOD__);


    }
}
