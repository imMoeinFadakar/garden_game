<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\UserBadge\StoreUserBadgeRequest;
use App\Http\Resources\V1\Admin\UserBadgeResource;
use App\Models\BadgeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBadgeController extends BaseUserController
{
    /**
     * get user badge 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userBadge = BadgeUser::query()
            ->orderBy("created_at")
            ->where("user_id",1)
            ->with(["badge:id,image_url"])
            ->get(['id','badge_id']);

        return $this->api(UserBadgeResource::collection($userBadge),__METHOD__);
    }

    /**
     * add new user badge
     * @param \App\Http\Requests\V1\Admin\UserBadge\StoreUserBadgeRequest $request
     * @param \App\Models\BadgeUser $badgeUser
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreUserBadgeRequest $request, BadgeUser $badgeUser)
    {
        $newRequest = $request->merge(["user_id"=> Auth::id() ]);
        $badgeUser = $badgeUser->addNewBadgeUser($newRequest);
        $badgeUser->user_id = null;
        return $this->api(new UserBadgeResource($badgeUser->toArray()),__METHOD__);
    }


}
