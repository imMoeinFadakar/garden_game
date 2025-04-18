<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Resources\V1\AuthAdminResource;
use App\Models\Admin;
use Carbon\Carbon;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\V1\Auth\AuthRequest;

class AuthController extends BaseAdminController
{
    /**
     * auth/login
     * @param \App\Http\Requests\V1\Auth\AuthRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        try{

            if(! $this->attemptLogin($request))
                return $this->api(null, __METHOD__,
                    'Email & password are not correct.',
                    false,
                    401);

           $admin = $this->getAdmin($request->email);
           if(! $admin){
                return $this->api(null, __METHOD__,
                    'User not found',
                    false,
                    400);
           }

           $admin->access_token = $admin->createAccessToken();

           return $this->api( new AuthAdminResource($admin->toArray()),  __METHOD__,"admin login successfuly" );
        }catch(Throwable $ex){
            return $this->api(null, __METHOD__,$ex->getMessage());

        }
    }

    /**
     * auth/logout
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function attemptLogin(Request $request)
    {
       return Auth::guard("admins")->attempt($request->only(['email', 'password']));
    }

    public function getAdmin($email)
    {
        return Admin::where("email",$email)
        ->first();
    }


    public function logout(Request $request)
    {
        $admin = auth()->user()->tokens()->delete();
        if ($admin) {
            return $this->api(null,message: 'Admin logged out');
        }
        return $this->api(null,message: 'Error', status: false, code: 500);
    }

    /**
     *auth/token_expire
     */
    public function isTokenValied(Request $request)
    {
        $user = $this->getCurrentlyUser();
        $token = $this->getUserToken($user);
        $isExpired = $this->isTokenExpired($token);

          return   $this->api([
                'expired' => $isExpired,
                'expires_at' => $token->expires_at,
                'message' => $isExpired ? 'Token has expired' : 'Token is valid',
            ],__METHOD__,"token status");


    }

    public function getCurrentlyUser()
    {
        $user = Auth::user();
        if($user)
            return $user;

        throw new \HttpResponseException(response()->json([
            "success" => false,
            "message" => "user is not found"
        ]));

    }

    public function getUserToken($user)
    {
        $token =  $user->currentAccessToken();
        if($token)
            return $token;


        throw new \HttpResponseException(response()->json([
            "success" => false,
            "message" => "token is not found"
        ]));

    }

    public function isTokenExpired($token): bool
    {
        return Carbon::now()->greaterThan($token->expires_at);
    }




}
