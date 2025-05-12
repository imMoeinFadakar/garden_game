<?php

namespace App\Http\Controllers\V1\User;


use App\Http\Requests\V1\User\FindUserRequest;
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
     * get user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = User::find(auth()->id());
        $user->id = null;
        return $this->api(new UserResource($user->toArray()),__METHOD__);
        
    }

    /**
     * find the auth user
     * @param \App\Http\Requests\V1\User\UserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function findingUser(UserRequest $request)
    {
        $findUser = User::query()
        ->where("telegram_id",$request->telegram_id)
        ->first(['telegram_id','name','username','gender','market_status','warehouse_status','user_status','token_amount','gem_amount','referral_code']);

       
        if(! $findUser)
            return $this->api(null,__METHOD__,"user is not exists");


        return $this->api(new UserResource($findUser->toArray()),__METHOD__);

    }
    /**
     * new referal / post
     * @param \App\Http\Requests\V1\User\newReffralRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function newReferral(newReffralRequest $request)
    {
        $invatingUser = $this->findUserByReferralCode($request->referral_code);
        $invantedUser = auth()->user();

        if($invantedUser->referral_code === $invatingUser->referral_code)
            return $this->api(null,__METHOD__,'cant enter your referral code');


        $isReferralExists = $this->isReferralExists($invatingUser->id,$invantedUser->id);
        if(! $isReferralExists){

            $newRefferal = UserReferral::query()
            ->create([
                "invented_user" => $invantedUser->id,
                "invading_user" => $invatingUser->id,
                'gender' => $invantedUser->gender === 'male'? 0 : 1 
            ]);

            return $this->api(null,__METHOD__,'referral was succesfull');
        }


        return $this->api(null,__METHOD__,'referral already exists');

    }
    /**
     * check referral exists before
     * @param int $invatingUserId
     * @param int $invantedUserId
     * @return bool
     */
    public function isReferralExists(int $invatingUserId,int $invantedUserId): bool
    {
        return UserReferral::query()
        ->where('invented_user',$invantedUserId)
        ->where('invading_user',$invatingUserId)
        ->exists();
    }
    /**
     * fidn user by Referral code
     * @param mixed $referrallCode
     * @return User|null
     */
    public function findUserByReferralCode($referrallCode)
    {
        return User::query()
        ->where("referral_code",$referrallCode)
        ->first();
    }

    public function findUserByReferral(FindUserRequest $request,User $user)
    {        
        $validated = $request->validated();

        $user = $user->query()
        ->where("referral_code",'=',$validated['referral_code'])
        ->first(['name','username','status']);

        return $this->api(new UserResource($user->toArray()),__METHOD__);

    }




}
