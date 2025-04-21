<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(1);

        return $this->api(new UserResource($user->toArray()),__METHOD__);
        
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
