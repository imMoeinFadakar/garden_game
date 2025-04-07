<?php

namespace App\Http\Controllers\V1\admin;

use App\Http\Requests\V1\Admin\UserBadge\StoreUserBadgeRequest;
use App\Http\Resources\V1\Admin\UserBadgeResource;
use App\Models\BadgeUser;
use Illuminate\Http\Request;

class BadgeUserController extends BaseAdminController
{
    /**
     * badgeuser/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $UserBadge = BadgeUser::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query) => $query->where("id", $request->id))
        ->when(isset($request->user_id),fn($query) => $query->where("user_id", $request->user_id))
        ->when(isset($request->badge_id),fn($query) => $query->where("badge_id", $request->badge_id))
        ->with(["user:id,name","badge:id,image_url"])
        ->get();

        return $this->api(UserBadgeResource::collection($UserBadge),__METHOD__);

    }

    /**
     * userbadge/store
     * @param \App\Http\Requests\V1\Admin\UserBadge\StoreUserBadgeRequest $request
     * @param \App\Models\BadgeUser $badgeUser
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreUserBadgeRequest $request, BadgeUser  $badgeUser)
    {
       $badgeUser =  $badgeUser->addNewBadgeUser($request);
        return $this->api(new UserBadgeResource($badgeUser->toArray()),__METHOD__);
    }

    /**
     * userbadge/show
     * @param \App\Models\BadgeUser $badgeUser
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(BadgeUser  $badgeUser)
    {
        return $this->api(new UserBadgeResource($badgeUser->toArray()),__METHOD__);

    }

    /**
     * badgeuser/update
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BadgeUser $badgeUser
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, BadgeUser  $badgeUser)
    {
        $badgeUser->updateBadgeUser($request);
        return $this->api(new UserBadgeResource($badgeUser->toArray()),__METHOD__);

    }

    /**
     * userbadge/user
     * @param \App\Models\BadgeUser $badgeUser
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(BadgeUser  $badgeUser)
    {
        $badgeUser->deleteBadgeUser();
        return $this->api(new UserBadgeResource($badgeUser->toArray()),__METHOD__);
    }
}
