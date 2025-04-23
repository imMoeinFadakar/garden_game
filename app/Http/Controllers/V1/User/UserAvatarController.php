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
     * Display a listing of the resource.
     */
    public function index()
    {
        $userAvatar = UserAvatar::query()
        ->where("user_id",auth()->id())
        ->with(["avatar:id,image_url"])
        ->first();

        return $this->api(new UserAvatarResource($userAvatar->toArray()),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
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


    public function isUseravatarExists(array $validatedRequest): bool
    {
        return UserAvatar::query()
        ->where("user_id",$validatedRequest["user_id"])
        ->where("user_id",auth()->id())
        ->exists();
    }


}
