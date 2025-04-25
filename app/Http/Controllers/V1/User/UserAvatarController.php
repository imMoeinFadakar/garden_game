<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UserAvatar\StoreUserAvatarRequest;
use App\Http\Resources\V1\User\UserAvatarResource;
use App\Models\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAvatarController extends Controller
{
    /**
     * return user avatar and its image
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userAvatar = UserAvatar::query()
        ->where("user_id",auth()->id())
        ->with(["avatar:id,image_url"])
        ->first();
        if($userAvatar){

            return $this->api(new UserAvatarResource($userAvatar->toArray()),__METHOD__);
        }else{
            return $this->api(null,__METHOD__,'dont have avatar');
        }
    }

    /**
     *  get user avatar
     * @param \App\Http\Requests\V1\User\UserAvatar\StoreUserAvatarRequest $request
     * @param \App\Models\UserAvatar $userAvatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreUserAvatarRequest $request,UserAvatar $userAvatar)
    {   
        $avatarExists = $this->isUseravatarExists($request->validated());
        if($avatarExists)
            return $this->api(null,__METHOD__,'you selected your avatar before');


        $validatedRequest = $request->validated();

       
        $validatedRequest["user_id"] = auth()->id();
        $userAvatar = $userAvatar->addNewUserAvatar($validatedRequest);
        return $this->api(new UserAvatarResource($userAvatar->toArray()),__METHOD__);
    }

    /**
     * is user select avatar before 
     * @param array $validatedRequest
     * @return bool
     */
    public function isUseravatarExists(array $validatedRequest): bool
    {
        return UserAvatar::query()
        ->where("user_id",auth()->id())
        ->exists();
    }


}
