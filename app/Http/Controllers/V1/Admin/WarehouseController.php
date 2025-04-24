<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Wherehouse\StorewherehouseRequest;
use App\Http\Requests\V1\Admin\Wherehouse\UpdatewherehouseRequest;
use App\Http\Resources\V1\Admin\WhereHouseResource;
use App\Models\Wherehouse;
use Illuminate\Http\Request;

class WarehouseController extends BaseAdminController
{
    /**
     * warehouse/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $warehouse = Wherehouse::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query)=> $query->where("id", $request->id))
        ->when(isset($request->user_id), fn($query)=> $query->where("user_id", $request->user_id))
        ->when(isset($request->wherehouse_level_id), fn($query)=> $query->where("warehouse_level_id", $request->wherehouse_level_id))
        ->with(['user:id,name','warehouse_level:id,level_number','farm:id,name'])
        ->get();

        return $this->api(WhereHouseResource::collection($warehouse),__METHOD__);
    }

    /**
     * warehouse/store
     * @param \App\Http\Requests\V1\Admin\Wherehouse\StorewherehouseRequest $request
     * @param \App\Models\Wherehouse $warehouse
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StorewherehouseRequest $request,Wherehouse $warehouse)
    {   
        // dd($request->all());
        $warehouse =  $warehouse->addnewWherehouse($request);
        $warehouse->load(['user:id,username','warehouse_level:id,level_number','farm:id,name']);
        return $this->api(new WhereHouseResource($warehouse->toArray()),__METHOD__);
    }

    /**
     * warehouse/show
     * @param \App\Models\Wherehouse $warehouse
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Wherehouse $warehouse)
    {
        $warehouse->load(['user:id,name','warehouse_level:id,level_number','farm:id,name']);
        return $this->api(new WhereHouseResource($warehouse->toArray()),__METHOD__);

    }

    /**
     * warehouse/update
     * @param \App\Http\Requests\V1\Admin\Wherehouse\UpdatewherehouseRequest $request
     * @param \App\Models\Wherehouse $wherehouse
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdatewherehouseRequest $request, Wherehouse $warehouse)
    {
        $warehouse->updateWherehouse($request);
        $warehouse->load(['user:id,name','warehouse_level:id,level_number','farm:id,name']);
        return $this->api(new WhereHouseResource($warehouse->toArray()),__METHOD__);

    }

    /**
     * warehouse/destroy
     * @param \App\Models\Wherehouse $warehouse
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Wherehouse $warehouse)
    {
        $warehouse->deleteWherehouse();
        $warehouse->load(['user:id,name','warehouse_level:id,level_number','farm:id,name']);
        return $this->api(new WhereHouseResource($warehouse->toArray()),__METHOD__);

    }
}
