<?php

namespace App\Http\Controllers\V1\Admin;

use App\Models\Admin;
use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

           return $this->api( $admin,  __METHOD__,"admin login succesfuly" );
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
            return $this->api(message: 'Admin logged out');
        }
        return $this->api(message: 'Error', status: false, code: 500);
    }


}
