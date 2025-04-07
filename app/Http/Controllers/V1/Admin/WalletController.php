<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Resources\V1\Admin\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends BaseAdminController
{
    /**
     * wallet/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $wallet = Wallet::query()
        ->orderBy("id")
        ->when(isset($request->id), fn($query)=> $query->where("id", $request->id))
        ->when(isset($request->gender), fn($query)=> $query->where("gender","like",'%'.$request->gender.'%'))
        ->with(['user:id,name,username'])
        ->get();

        return $this->api(WalletResource::collection($wallet),__METHOD__);
    }
    /**
     * wallet/show
     * @param \App\Models\Wallet $wallet
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Wallet $wallet)
    {
        return $this->api(new WalletResource($wallet->toArray()),__METHOD__);
    }

}
