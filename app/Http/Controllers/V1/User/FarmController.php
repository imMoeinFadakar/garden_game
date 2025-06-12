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


        Cache::forget("index_farm");

        
        $farms = Cache::rememberForever('index_farm', function () {
            return Farms::all();
        });

      
        $farms->map(function ($farm) {
            $randomPrice = $this->getRandomPrice(
                $farm->min_token_value,
                $farm->max_token_value,
                $farm->id
            );

            $farm->setAttribute('random_price', $randomPrice);
        });

        return $this->api(FarmResource::collection($farms), __METHOD__);
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

        $farms = Cache::remember($cacheKey,
        now()->addDay(),
        function() use($farms,$id){

            return $farms->find($id);

        });
        return $this->api(new FarmResource($farms),__METHOD__);
    }


   protected function getRandomPrice(int $minPrice, int $maxPrice, int $farmId)
{
        $cacheKey = "random_price_farm_" . $farmId;

        return Cache::remember($cacheKey, now()->addDay(), function () use ($minPrice, $maxPrice) {
            return random_int($minPrice, $maxPrice);
        });
}




}
