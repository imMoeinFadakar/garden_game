<?php

namespace App\Http\Controllers\V1\User;


use App\Http\Requests\V1\User\FindUserRequest;
use App\Http\Requests\V1\User\newReffralRequest;
use App\Http\Requests\V1\User\UserRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Models\CartUser;
use App\Models\TeammateRequest;
use App\Models\User;
use App\Models\UserReferral;
use App\Trait\UserActiveTrait;
use Cache;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends BaseUserController
{   
    
    /**
     * get user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAuthUser()
    {   
        
        $user = auth()->user();
        $user->id = null;
        return $this->api(new UserResource($user->toArray()),__METHOD__);
        
    }

    /**
     * find the auth user
     * @param \App\Http\Requests\V1\User\UserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function findUserByTelegarmId(UserRequest $request)
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
    public function addNewReferral(newReffralRequest $request)
    {

        

        $invatingUser = $this->findUserByReferralCode($request->referral_code);
        $invantedUser = auth()->user();

        if($invantedUser->referral_code === $invatingUser->referral_code)
            return $this->api(null,__METHOD__,'cant enter your referral code');


        $isReferralExists = $this->isReferralExists($invatingUser->id,$invantedUser->id);

        if(! $isReferralExists){

            $invantedUser->has_parent =  "true";
            $invantedUser->save();

             UserReferral::query()
            ->create([
                "invented_user" => $invantedUser->id,
                "invading_user" => $invatingUser->id,
                'gender' => $invantedUser->gender === 'male'? 0 : 1 
            ]);

             $cacheKey = "referral_tree_with_farms_" . auth()->id();
            Cache::forget($cacheKey);


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

    public function findUserByTransferCartNumber(FindUserRequest $request)
    {        
        $data = $request->validated();

        $reciverUserId = $this->findUserId($data);

        $reciverUser = User::find($reciverUserId);

        return $this->api(new UserResource($reciverUser->toArray()),__METHOD__);
    }

   
    protected function findUserId(array $data)
    {
        return CartUser::query()
        ->where('cart_number',$data['cart_number'])
        ->value('user_id');
    }
    

    public function addNewTeammate(array $parenlessUsers)
    {

        foreach($parenlessUsers as $user){

            $teammateRequest = $this->getPendingTeammateRequest();

            $this->updateTeammateRequest($teammateRequest);

            $this->addNewTeammateForUser($user,$teammateRequest->user_id);

            $user->has_parent = "true";
            $user->save();
        }

        return;

    }


    public function getPendingTeammateRequest()
    {
        return TeammateRequest::query()
            ->orderBy('created_at', 'asc')
            ->where('status','pending')
            ->first() ?? null;

    }

    public function findParentlessUser()
    {
        return User::query()
        ->where('has_parent','false')
        ->first() ?? null;

    }

    public function getAllParentlessUsers()
    {
        return User::query()
        ->where('has_parent','false')
        ->get() ?? null;
    }



    public function addNewTeammateForUser($invantedUser,int $invatingUserId)
    {
        return UserReferral::create([
            "invented_user" => $invantedUser['id'],
            "gender" => $invantedUser['gender'] === "male" ? 1 : 0,
            "invading_user" => $invatingUserId
        ]);
    }

    public function updateTeammateRequest($teammateRequest)
    {
        $teammateRequest->status = "done";
        $teammateRequest->save();
        return;
    }

}
