<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\GameSetting\GameSettingRequest;
use App\Http\Resources\V1\Admin\GameSettingResource;
use App\Models\GameSetting;
use Illuminate\Http\Request;

class GameSettingController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allGameSetting = GameSetting::all();
        return $this->api(GameSettingResource::collection($allGameSetting),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GameSettingRequest $request, GameSetting $gameSetting)
    {
       $gameSetting =  $gameSetting->addNewGameSetting($request);
        return $this->api(new GameSettingResource($gameSetting->toArray()),__METHOD__);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GameSetting $gameSetting)
    {
        $gameSetting->updateGameSetting($request);
        return $this->api(new GameSettingResource($gameSetting->toArray()),__METHOD__);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameSetting $gameSetting)
    {
        $gameSetting->deleteGameSetting();
        return $this->api(new GameSettingResource($gameSetting->toArray()),__METHOD__);
        
    }
}
