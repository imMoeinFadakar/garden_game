<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\UserAvatar\StoreUserAvatar;
use App\Http\Resources\V1\Admin\UserAvatarResource;
use App\Models\UserAvatar;
use Illuminate\Http\Request;

class UserAvatarController extends BaseAdminController
{
    /**
     * useravatar/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $UserAvatar = UserAvatar::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query)=> $query->where("id", $request->id))
        ->when(isset($request->user_id),fn($query)=> $query->where("user_id", $request->user_id))
        ->when(isset($request->avatar_id),fn($query)=> $query->where("avatar_id", $request->avatar_id))
        ->with(["user:id,name","avatar:id,image_url"])
        ->get();

        return $this->api(UserAvatarResource::collection($UserAvatar),__METHOD__);
    }

    /**
     * useravatar/store
     * @param \App\Http\Requests\V1\Admin\UserAvatar\StoreUserAvatar $request
     * @param \App\Models\UserAvatar $userAvatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreUserAvatar $request , UserAvatar $userAvatar)
    {
        $userAvatar = $userAvatar->addNewUserAvatar($request->validated());
        return $this->api(new UserAvatarResource($userAvatar->toArray()) ,__METHOD__ );
    }
    /**
     * UserAvatar/show
     * @param \App\Models\UserAvatar $userAvatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(UserAvatar $userAvatar)
    {
        return $this->api(new UserAvatarResource($userAvatar->toArray()) ,__METHOD__ );
    }

    /**
     * UserAvavtar/update
     * @param \App\Http\Requests\V1\Admin\UserAvatar\StoreUserAvatar $request
     * @param \App\Models\UserAvatar $user_avatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(StoreUserAvatar $request , UserAvatar $user_avatar)
    {
        $user_avatar->updateUserAvatar($request);
        return $this->api(new UserAvatarResource($user_avatar->toArray()) ,__METHOD__ );

    }
    /**
     * Useravatar/destroy
     * @param \App\Models\UserAvatar $user_avatar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(UserAvatar $user_avatar)
    {
        $user_avatar->deleteUserAvatar();
        return $this->api(new UserAvatarResource($user_avatar->toArray()) ,__METHOD__ );

    }
}
