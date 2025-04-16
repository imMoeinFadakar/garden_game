<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Resources\V1\Admin\WarehouseProductResource;
use App\Models\WarehouseProducts;
use Illuminate\Http\Request;

class WarehouseProductController extends BaseAdminController
{
    /**
     * warehouseprooduct/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
       $WarehouseProducts=  WarehouseProducts::query()
        ->when(isset($request->id), fn($query)=> $query->where("id", $request->id))
        ->when(isset($request->wherehouse_id), fn($query)=> $query->where("warehouse_id", $request->wherehouse_id))
        ->when(isset($request->product_id), fn($query)=> $query->where("product_id", $request->product_id))
        ->with(['product:id,name','wherehouse.user:id,name,username'])
        ->get();

        return $this->api(WarehouseProductResource::collection($WarehouseProducts),__METHOD__);

    }

    /**
     * warehouse/show
     * @param \App\Models\WarehouseProducts $warehouseProducts
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(WarehouseProducts $warehouseProduct)
    {
        $warehouseProduct->load(['product:id,name','wherehouse.user:id,name,username']);
        return $this->api(new WarehouseProductResource($warehouseProduct->toArray()),__METHOD__);
    }
}
