<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Resources\V1\Admin\UserReffrallmanagmentResource;
use App\Models\UserReffralManagment;
use Illuminate\Http\Request;

class UserReferralManagementController extends BaseAdminController
{
    /**
     * userRefferalManagemant/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $UseReferralManagement = UserReffralManagment::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query) =>  $query->where("id", $request->id))
        ->when(isset($request->user_id), fn($query) =>  $query->where("user_id", $request->user_id))
        ->with(["user:id,name"])
        ->get();

        return $this->api(UserReffrallmanagmentResource::collection($UseReferralManagement),__METHOD__);
    }

}
