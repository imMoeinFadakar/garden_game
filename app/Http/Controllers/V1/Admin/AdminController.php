<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Admin\StoreAdminRequest;
use App\Http\Requests\V1\Admin\Admin\UpdateAdminRequest;
use App\Http\Resources\V1\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends BaseAdminController
{   


  
    /**
     * admin/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $admin = Admin::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query)=> $query->where("id", $request->id))
        ->when(isset($request->fullname), fn($query)=> $query->where("fullname", "like",'%'.$request->fullname.'%'))
        ->when(isset($request->email), fn($query)=> $query->where("email", "like",'%'.$request->email.'%'))
        ->get();

        return $this->api(AdminResource::collection($admin),__METHOD__);

    }

    /**
     * admin/store
     * @param \App\Http\Requests\V1\Admin\Admin\StoreAdminRequest $request
     * @param \App\Models\Admin $admin
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreAdminRequest $request,Admin $admin)
    {

        $admin  = $admin->addNewAdmin($request);
        return $this->api(new AdminResource($admin->toArray()),__METHOD__);
    }

    /**
     * admin/show
     * @param \App\Models\Admin $admin
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Admin $admin)
    {
        return $this->api(new AdminResource($admin->toArray()),__METHOD__);

    }

    /**
     * admin/update
     * @param \App\Http\Requests\V1\Admin\Admin\UpdateAdminRequest $request
     * @param \App\Models\Admin $admin
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateAdminRequest $request,Admin $admin)
    {
        $admin->updateAdmin($request);
        return $this->api(new AdminResource($admin->toArray()),__METHOD__);

    }

    /**
     * admin/delete
     * @param \App\Models\Admin $admin
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Admin $admin)
    {
        $admin->deleteAdmin();
        return $this->api(new AdminResource($admin->toArray()),__METHOD__);

    }
}
