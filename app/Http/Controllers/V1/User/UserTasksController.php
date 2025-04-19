<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\UserTask\UserTaskRequest;
use App\Http\Resources\V1\User\UserTaskResource;
use App\Models\Tasks;
use App\Models\UserTask;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            ->with(["task:id,title,gem_reward,token_reward"])
            ->get();

        return $this->api(UserTaskResource::collection($userTask),__METHOD__);

    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(UserTaskRequest $request, UserTask $userTask)
    {   

        $this->isTaskDoneBefore($request);
    
        $validatedRequest = $request->validated();
        $validatedRequest["user_id"] = 1; // auth::id()
        $userTask = $userTask->addNewUserTask($validatedRequest);
        return $this->api(new UserTaskResource($userTask->toArray()), __METHOD__);
    }

    public function isTaskDoneBefore($request): bool
    {
        $task =  UserTask::query()
        ->where("user_id",1)
        ->where("task_id",$request->task_id)
        ->exists();

        if($task)
            throw new HttpResponseException(response()->json([
            "success" => false,
            "message" => "ypu had done this task before"
            ]));

        return true;
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
