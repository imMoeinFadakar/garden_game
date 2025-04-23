<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\AvatarResource;
use App\Models\Avatar;
use Illuminate\Http\Request;

class AvatarController extends BaseUserController
{
    /**
     * get all avatar from db
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $Avatar = Avatar::query()
        ->orderBy("id")
        ->get();

        return $this->api(AvatarResource::collection($Avatar),__METHOD__);
    }


}
