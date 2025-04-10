<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\FarmResource;
use App\Models\Farms;
use Illuminate\Http\Request;

class FarmController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farms = Farms::query()
            ->orderBy("id")
            ->get();

        return $this->api(FarmResource::collection($farms),__METHOD__);
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
