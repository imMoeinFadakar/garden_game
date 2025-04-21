<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UserAvatar\StoreUserAvatarRequest;
use App\Http\Resources\V1\User\UserAvatarResource;
use App\Models\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAvatarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserAvatarRequest $request,UserAvatar $userAvatar)
    {
        $validatedRequest = $request->validated();
        $validatedRequest["user_id"] = auth()->id();
        $userAvatar = $userAvatar->addNewUserAvatar($validatedRequest);
        return $this->api(new UserAvatarResource($userAvatar->toArray()),__METHOD__);
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
