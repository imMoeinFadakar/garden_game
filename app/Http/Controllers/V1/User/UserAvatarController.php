<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UserAvatar\StoreUserAvatarRequest;
use App\Http\Resources\V1\User\UserAvatarResource;
use App\Models\UserAvatar;
use Illuminate\Support\Facades\Cache;

class UserAvatarController extends Controller
{
  
    public function getUserAvatar()
    {
        $cacheKey = 'user_avatar_' . auth()->id();

        $userAvatar = Cache::rememberForever($cacheKey, function () {
            return UserAvatar::query()
                ->where("user_id", auth()->id())
                ->with(["avatar:id,image_url"])
                ->first(['id', 'avatar_id']);
        });

        if ($userAvatar) {
            $userAvatar->user_id = null;
            return $this->api(new UserAvatarResource($userAvatar->toArray()), __METHOD__);
        }

        return $this->api(null, __METHOD__, 'dont have avatar');
    }

   
    public function addNewAvatarForUser(StoreUserAvatarRequest $request, UserAvatar $userAvatar)
    {
        $cacheKey = 'user_avatar_' . auth()->id();

        if (Cache::has($cacheKey) || $this->isUseravatarExists()) {
            return $this->api(null, __METHOD__, 'you selected your avatar before');
        }

        $validatedRequest = $request->validated();
        $validatedRequest["user_id"] = auth()->id();

        $userAvatar = $userAvatar->addNewUserAvatar($validatedRequest);

        // ذخیره دائمی در کش
        Cache::forever($cacheKey, $userAvatar->load('avatar:id,image_url'));

        $userAvatar->user_id = null;
        return $this->api(new UserAvatarResource($userAvatar->toArray()), __METHOD__);
    }

    /**
     * Check from DB if user already selected avatar
     */
    protected function isUseravatarExists(): bool
    {
        return UserAvatar::query()
            ->where("user_id", auth()->id())
            ->exists();
    }
}

