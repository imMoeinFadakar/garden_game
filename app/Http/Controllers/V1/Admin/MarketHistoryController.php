<?php

namespace App\Http\Controllers\V1\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\admin\MarketHistoryResource;
use App\Models\MarketHistory;
use Illuminate\Http\Request;

class MarketHistoryController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marketHistory = MarketHistory::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query) => $query->where("id", $request->id))
        ->when(isset($request->user_id), fn($query) => $query->where("user_id", $request->user_id))
        ->when(isset($request->product_amount), fn($query) => $query->where("product_amount", $request->product_amount))
        ->when(isset($request->token_amount), fn($query) => $query->where("token_amount", $request->token_amount))
        ->get(); 

        return $this->api(MarketHistoryResource::collection($marketHistory),__METHOD__);

    }



    /**
     * Display the specified resource.
     */
    public function show(MarketHistory $marketHistory)
    {
        return $this->api(new MarketHistoryResource($marketHistory->toArray()),__METHOD__);
    }

 

}
