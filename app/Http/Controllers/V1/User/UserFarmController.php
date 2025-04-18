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
     * Display a listing of the resource.
     */
    public function index()
    {
        $userFarm = UserFarms::query()
            ->orderBy("id")
            ->where("user_id",1)
            ->with(["farm:id,image_url,flage_image_url,description,power"])
            ->get();

        return $this->api(UserFarmResource::collection($userFarm),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BuyfarmRequest $request,UserFarms $userFarms)
    {



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
