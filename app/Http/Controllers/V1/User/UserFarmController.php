<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\BuyFarm\BuyfarmRequest;
use App\Http\Resources\V1\User\UserFarmResource;
use App\Models\UserFarms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFarmController extends BaseUserController
{
    /**
     * get user farm
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userFarm = UserFarms::query()
            ->orderBy("id")
            ->where("user_id",auth()->id())
            ->with(["farm:id,name,farm_image_url,flage_image_url,description,power,prodcut_image_url"])
            ->get(['id',"farm_id",'farm_power']);

        return $this->api(UserFarmResource::collection($userFarm),__METHOD__);
    }


}
