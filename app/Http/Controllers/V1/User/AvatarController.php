<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\AvatarResource;
use App\Models\Avatar;
use Illuminate\Http\Request;

class AvatarController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Avatar = Avatar::query()
        ->orderBy("id")
        ->get();

        return $this->api(AvatarResource::collection($Avatar),__METHOD__);
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
