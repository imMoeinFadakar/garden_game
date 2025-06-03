<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\FarmResource;
use App\Models\Farms;
use Cache;
use Illuminate\Http\Request;

class FarmController extends BaseUserController
{
    /**
     * farm/index
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllExistsFarmInGame()
    {
        $cacheKey = "index_farm";
        $farms = Cache::rememberForever($cacheKey,function(){

            return Farms::all();


        });

        return $this->api(FarmResource::collection($farms),__METHOD__);
    }

    /**
     * show/farm
     * @param \App\Models\Farms $farms
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Farms $farms,$id)
    {   
        $cacheKey = "show_farm";
        $farms = Cache::rememberForever($cacheKey,function() use($farms,$id){

            return $farms->find($id);

        });
        return $this->api(new FarmResource($farms),__METHOD__);
    }


}
