<?php

namespace App\Http\Controllers\V1\User;


use App\Http\Requests\V1\User\newReffralRequest;
use App\Http\Requests\V1\User\UserRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use App\Models\UserReferral;
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

    public function newReferral(newReffralRequest $request)
    {
        $invatingUser = $this->findUserByReferralCode($request->referral_code);
        $invantedUser = auth()->user();

        $isReferralExists = $this->isReferralExists($invatingUser->id,$invantedUser->id);
        if(! $isReferralExists){

            $newRefferal = UserReferral::query()
            ->create([
                "invented_user" => $invantedUser->id,
                "invading_user" => $invatingUser->id,
                'gender' => $invantedUser->gender === 'male'? 0 : 1 
            ]);

            return $this->api(new UserResource($newRefferal->toArray()),__METHOD__);
        }


        return $this->api(null,__METHOD__,'referral already exists');

    }
    public function isReferralExists(int $invatingUserId,int $invantedUserId): bool
    {
        return UserReferral::query()
        ->where('invented_user',$invantedUserId)
        ->where('invading_user',$invatingUserId)
        ->exists();
    }

    public function findUserByReferralCode($referrallCode)
    {
        return User::query()
        ->where("referral_code",$referrallCode)
        ->first();
    }

}
