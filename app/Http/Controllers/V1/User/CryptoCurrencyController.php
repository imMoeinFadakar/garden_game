<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\CryptoCurrencyResource;
use App\Models\cryptocurrency;
use Illuminate\Http\Request;

class CryptoCurrencyController extends BaseUserController
{
    /**
     * get list of user crypto currency history
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function index(Request $request)
    {
       $cryptocurrency = cryptocurrency::query()
         ->where("user_id",auth()->id()) // add auth::id() later
         ->orderBy("id")
         ->get();

         return $this->api(CryptoCurrencyResource::collection($cryptocurrency),__METHOD__);
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
    public function show(string $id)
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
