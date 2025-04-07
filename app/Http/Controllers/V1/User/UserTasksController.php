<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\UserTask\StoreUserTasksRequest;
use App\Http\Resources\V1\User\UserTaskResource;
use App\Models\UserTask;
use Illuminate\Http\Request;

class UserTasksController extends BaseUserController
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
    public function store(StoreUserTasksRequest $request,UserTask $userTask)
    {
        $userTask = $userTask->addNewUserTask($request);
        return $this->api(new UserTaskResource($userTask->toArray()), __METHOD__);
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
