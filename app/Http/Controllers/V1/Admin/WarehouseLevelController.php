<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Wherehouselevel\StorewherehouselevelRequest;
use App\Http\Requests\V1\Admin\Wherehouselevel\UpdatewherehouselevelRequest;
use App\Http\Resources\V1\Admin\WhereHosueLevelResource;
use App\Models\Warehouse_level;
use App\Models\WarehouseLevel;
use Illuminate\Http\Request;

class WarehouseLevelController extends BaseAdminController
{
    /**
     * waehouselevel/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $WarehouseLevel = WarehouseLevel::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query)=> $query->where("id", $request->id))
        ->when(isset($request->level_number), fn($query)=> $query->where("level_number",$request->level_number))
        ->get();

        return $this->api(WhereHosueLevelResource::collection($WarehouseLevel),__METHOD__);
    }

    /**
     * warehouse/store
     * @param \App\Http\Requests\V1\Admin\Wherehouselevel\StorewherehouselevelRequest $request
     * @param \App\Models\WarehouseLevel $warehouseLevel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StorewherehouselevelRequest $request, WarehouseLevel $warehouseLevel)
    {
        $warehouseLevel= $warehouseLevel->addNewWarehouseLevel($request);
        return $this->api(new WhereHosueLevelResource($warehouseLevel->toArray()),__METHOD__);
    }
    /**
     * waehouse/show
     * @param \App\Models\WarehouseLevel $warehouseLevel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(WarehouseLevel $warehouseLevel)
    {
        return $this->api(new WhereHosueLevelResource($warehouseLevel->toArray()),__METHOD__);

    }

    /**
     * warehouse/update
     * @param \App\Http\Requests\V1\Admin\Wherehouselevel\UpdatewherehouselevelRequest $request
     * @param \App\Models\WarehouseLevel $warehouseLevel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdatewherehouselevelRequest $request, WarehouseLevel $warehouseLevel)
    {
        $warehouseLevel->updateWarehouseLevel($request);
        return $this->api(new WhereHosueLevelResource($warehouseLevel->toArray()),__METHOD__);

    }

    /**
     * warehouse/destroy
     * @param \App\Models\WarehouseLevel $warehouseLevel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(WarehouseLevel $warehouseLevel)
    {
        $warehouseLevel->deleteWarehouseLevel();
        return $this->api(new WhereHosueLevelResource($warehouseLevel->toArray()),__METHOD__);

    }
}
