<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * wallet that own by user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function userWallet()
    {
        $UserWallet = Wallet::query()
        ->where("user_id", auth()->id())
        ->first();

        return $this->api(new WalletResource($UserWallet->toArray()),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
