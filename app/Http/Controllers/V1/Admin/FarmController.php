<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Farm\StoreFarmRequest;
use App\Http\Requests\V1\Admin\Farm\UpdateFarmRequest;
use App\Http\Resources\V1\Admin\FarmResource;
use App\Models\Farms;
use Illuminate\Http\Request;

class FarmController extends BaseAdminController
{
    /**
     * farm/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
       $farms = Farms::query()
       ->orderBy("id")
       ->when(isset($request->id), fn($query)=>$query->where("id",$request->id))
       ->when(isset($request->require_token), fn($query)=>$query->where("require_token",$request->require_token))
       ->when(isset($request->require_gem), fn($query)=>$query->where("require_gem",$request->require_gem))
       ->when(isset($request->require_reffral), fn($query)=>$query->where("require_reffral",$request->require_reffral))
       ->get();

       return $this->api(FarmResource::collection($farms),__METHOD__);
    }

    /**
     * farm/store
     * @param \App\Http\Requests\V1\Admin\Farm\StoreFarmRequest $request
     * @param \App\Models\Farms $farms
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreFarmRequest $request,Farms $farms)
    {
        $farms = $farms->addNewFarm($request);
        return $this->api(new FarmResource($farms->toArray()),__METHOD__);
    }
    /**
     * farm/show
     * @param \App\Models\Farms $farms
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Farms $farm)
    {
        return $this->api(new FarmResource($farm->toArray()),__METHOD__);

    }

    /**
     * farm/update
     * @param \App\Http\Requests\V1\Admin\Farm\UpdateFarmRequest $request
     * @param \App\Models\Farms $farm
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateFarmRequest $request, Farms $farm )
    {
        $farm->updateFarm($request);
        return $this->api(new FarmResource($farm->toArray()),__METHOD__);

    }

    /**
     * farm/destroy
     * @param \App\Models\Farms $farm
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Farms $farm)
    {
        $farm->deleteFarm();
        return $this->api(new FarmResource($farm->toArray()),__METHOD__);

    }
}
