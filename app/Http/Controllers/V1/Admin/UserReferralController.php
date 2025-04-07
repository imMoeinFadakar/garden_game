<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\V1\Admin\UseReffral;
use App\Http\Resources\V1\Admin\UserReffralResource;
use App\Models\UserReferral;
use Illuminate\Http\Request;

class UserReferralController extends BaseAdminController
{
    /**
     * userrefferal/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $UseReferral =   UserReferral::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query) =>  $query->where("id", $request->id))
        ->when(isset($request->invented_user), fn($query) =>  $query->where("invented_user", $request->invented_user))
        ->when(isset($request->invating_user), fn($query) =>  $query->where("invating_user", $request->invating_user))
        ->with(["reffred:id,name","reffring:id,name"])
        ->get();

        return $this->api(UserReffralResource::collection($UseReferral),__METHOD__);

    }

}
