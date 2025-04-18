<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\TransferResource;
use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * Transfer/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $Transfer = Transfer::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query) =>  $query->where("id", $request->id))
        ->when(isset($request->gem_amount),fn($query) =>  $query->where("gem_amount", $request->gem_amount))
        ->when(isset($request->from_user),fn($query) =>  $query->where("from_user", $request->from_user))
        ->when(isset($request->to_user),fn($query) =>  $query->where("to_user", $request->to_user))
        ->with(["from_wallet.user","to_wallet.user"])
        ->get();

        return $this->api(TransferResource::collection($Transfer),__METHOD__);
    }

}
