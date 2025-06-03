<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\MarketResource;
use App\Models\MarketHistory;
use App\Services\MarketService;
use Exception;
use App\Http\Requests\V1\User\MarketRequest;

class MarketController extends BaseUserController
{


    protected MarketService $marketService;

    /**
     * Class constructor.
     */
    public function __construct(MarketService $marketService)
    {
        $this->marketService = $marketService;
    }


    /**
     * get user sell product history
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getUserMarketHistory()
    {
        $cacheKey = "user_market_history_" . auth()->id();

        $marketHistory = cache()->remember($cacheKey,120,function(){

            return MarketHistory::query()
            ->where('user_id',auth()->id())
            ->with(['farm:id,name,prodcut_image_url'])
            ->get(['id','product_amount','token_amount','created_at','farm_id']);

        });

  

        return $this->api(MarketResource::collection($marketHistory),__METHOD__);
    }


   
    public function sellProduct(MarketRequest $request,MarketHistory $marketHistory)
    { 

        try{

            $result = $this->marketService->sellProduct(auth()->user(),$request->validated());
            return $this->api($result,__METHOD__,"product was sold successfuly");

        }catch(Exception $e){

            return $this->errorResponse($e->getMessage(), 400);
        }

    }


  
    

}
