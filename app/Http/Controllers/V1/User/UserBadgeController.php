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

    public function index()
    {
        $userBadge = BadgeUser::query()
            ->orderBy("created_at")
            ->where("user_id",Auth::id())
            ->get();

        return $this->api(UserBadgeResource::collection($userBadge),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserBadgeRequest $request, BadgeUser $badgeUser)
    {
        $newRequest = $request->merge(["user_id"=> Auth::id() ]);
        $badgeUser = $badgeUser->addNewBadgeUser($newRequest);

        return $this->api(new UserBadgeResource($badgeUser->toArray()),__METHOD__);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
