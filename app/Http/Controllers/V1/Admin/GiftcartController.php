<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Giftcart\StoreGiftcart;
use App\Http\Resources\V1\Admin\GiftcartResource;
use App\Models\Giftcart;
use Illuminate\Http\Request;

class GiftcartController extends BaseAdminController
{
    /**
     * giftcart/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $farms = Giftcart::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query)=>$query->where("id",$request->id))
        ->when(isset($request->value), fn($query)=>$query->where("value",$request->value))
        ->get();

        return $this->api(GiftcartResource::collection($farms),__METHOD__);
    }

    /**
     * giftcart/store
     * @param \App\Http\Requests\V1\Admin\Giftcart\StoreGiftcart $request
     * @param \App\Models\Giftcart $giftcart
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreGiftcart $request,Giftcart $giftcart)
    {
        $giftcart = $giftcart->addNewGiftcart($request,$request->count);
        return $this->api($giftcart,__METHOD__);
    }


    /**
     * giftcart/destroy
     * @param \App\Models\Giftcart $giftcart
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Giftcart $giftcart)
    {
        $giftcart->deleteGiftcart();
        return $this->api(new GiftcartResource($giftcart->toArray()),__METHOD__);

    }
}
