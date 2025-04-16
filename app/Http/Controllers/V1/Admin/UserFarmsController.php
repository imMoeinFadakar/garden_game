<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\UserFarm\StoreUserFarmRequest;
use App\Http\Requests\V1\Admin\UserFarm\UpdateUserFarmRequest;
use App\Http\Resources\V1\Admin\UserFarmsResource;
use App\Models\UserFarms;
use Illuminate\Http\Request;

class UserFarmsController extends BaseAdminController
{
    /**
     * userfarm/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $UserFarms = UserFarms::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query) => $query->where("id", $request->id))
        ->when(isset($request->user_id),fn($query) => $query->where("user_id", $request->user_id))
        ->when(isset($request->task_id),fn($query) => $query->where("task_id", $request->task_id))
        ->with(["user:id,name,username","farm:id,name"])
        ->get();

        return  $this->api(UserFarmsResource::collection($UserFarms),__METHOD__);
    }

    /**
     * userfarms/store
     * @param \App\Http\Requests\V1\Admin\UserFarm\StoreUserFarmRequest $request
     * @param \App\Models\UserFarms $userFarms
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request,UserFarms $userFarm)
    {
        $userFarm = $userFarm->addNewUserFarms($request);
        $userFarm->load(["user:id,name,username","farm:id,name"]);
        return $this->api(new UserFarmsResource($userFarm->toArray()),__METHOD__);
    }

    /**
     * userfarms/show
     * @param \App\Models\UserFarms $userFarm
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(UserFarms $userFarm)
    {
        $userFarm->load(["user:id,name,username","farm:id,name"]);
        return $this->api(new UserFarmsResource($userFarm->toArray()),__METHOD__);
    }

    /**
     * userfarm/update
     * @param \App\Http\Requests\V1\Admin\UserFarm\UpdateUserFarmRequest $request
     * @param \App\Models\UserFarms $userFarms
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserFarmRequest $request,UserFarms $userFarm)
    {
        $userFarm->updateUserFarms($request);
        $userFarm->load(["user:id,name,username","farm:id,name"]);
        return $this->api(new UserFarmsResource($userFarm->toArray()),__METHOD__);

    }

    /**
     * userfarm/destroy
     * @param \App\Models\UserFarms $userFarms
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(UserFarms $userFarm)
    {
        $userFarm->deleteUserFarms();
        return $this->api(new UserFarmsResource($userFarm->toArray()),__METHOD__);

    }

}
