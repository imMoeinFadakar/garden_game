<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\BadgeFarms\StoreBadgeFarmsRequest;
use App\Http\Requests\V1\Admin\BadgeFarms\UpdateBadgeFarmsRequest;
use App\Http\Resources\V1\Admin\BadgeFarmsResource;
use App\Models\BadgeFarm;
use Illuminate\Http\Request;

class BadgeFarmsController extends BaseAdminController
{
    /**
     * badgefarms/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request   $request)
    {
        $BadgeFarm = BadgeFarm::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query) => $query->where("id", $request->id))
        ->when(isset($request->badge_id),fn($query) => $query->where("badge_id", $request->badge_id))
        ->when(isset($request->farm_id),fn($query) => $query->where("farm_id", $request->farm_id))
        ->with(["badge:id,image_url","farm:id,name"])
        ->get();

        return $this->api(BadgeFarmsResource::collection($BadgeFarm),__METHOD__);
    }

    /**
     * badgefarms/store
     * @param \App\Http\Requests\V1\Admin\BadgeFarms\StoreBadgeFarmsRequest $request
     * @param \App\Models\BadgeFarm $badgeFarm
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreBadgeFarmsRequest $request,BadgeFarm $badgeFarm)
    {
        $badgeFarm = $badgeFarm->addNewBadgeFarm($request);
        $badgeFarm->load(["badge:id,image_url","farm:id,name"]);
        return $this->api(new BadgeFarmsResource($badgeFarm->toArray()),__METHOD__);

    }
    /**
     * badgefarm/show
     * @param \App\Models\BadgeFarm $badgeFarm
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(BadgeFarm $badgeFarm)
    {   
        $badgeFarm->load(["badge:id,image_url","farm:id,name"]);
        return $this->api(new BadgeFarmsResource($badgeFarm->toArray()),__METHOD__);

    }

    /**
     *  badgefarm/update
     * @param \App\Http\Requests\V1\Admin\BadgeFarms\UpdateBadgeFarmsRequest $request
     * @param \App\Models\BadgeFarm $badgeFarm
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateBadgeFarmsRequest $request,BadgeFarm $badgeFarm)
    {
        $badgeFarm->updateBadgeFarm($request);
        $badgeFarm->load(["badge:id,image_url","farm:id,name"]);
        return $this->api(new BadgeFarmsResource($badgeFarm->toArray()),__METHOD__);
    }

    /**
     * badgefarms/destroy
     * @param \App\Models\BadgeFarm $badgeFarm
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(BadgeFarm $badgeFarm)
    {
        $badgeFarm->deleteBadgeFarm();
        return $this->api(new BadgeFarmsResource($badgeFarm->toArray()),__METHOD__);
    }
}
