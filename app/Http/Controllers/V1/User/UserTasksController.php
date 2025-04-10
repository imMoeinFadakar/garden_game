<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\UserTask\UserTaskRequest;
use App\Http\Resources\V1\User\UserTaskResource;
use App\Models\Tasks;
use App\Models\UserTask;
use Illuminate\Http\Request;

class UserTasksController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $userTask = UserTask::query()
            ->orderBy("id")
            ->where("user_id", 1)
            ->get();

        return $this->api(UserTaskResource::collection($userTask),__METHOD__);

    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(UserTaskRequest $request, UserTask $userTask)
    {
        $validatedRequest = $request->validated();
        $newRequest = $validatedRequest->merge(["user_id" => 1]);
        $userTask = $userTask->addNewUserTask($newRequest);
        return $this->api(new UserTaskResource($userTask->toArray()), __METHOD__);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // not
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // not
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // not
    }
}
