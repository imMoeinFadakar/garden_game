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
     * get task that user had done before
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $userTask = UserTask::query()
            ->orderBy("id")
            ->where("user_id", auth()->id())
            ->with(["task:id,title,gem_reward,token_reward"])
            ->get(['id','task_id','created_at']);

        return $this->api(UserTaskResource::collection($userTask),__METHOD__);

    }

    /**
     * add new user task
     * @param \App\Http\Requests\V1\User\UserTask\UserTaskRequest $request
     * @param \App\Models\UserTask $userTask
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(UserTaskRequest $request, UserTask $userTask)
    {   

        $done =  $this->isTaskDoneBefore($request);

        if(! $done)
            return $this->errorResponse('you had done this task before',400);



        $validatedRequest = $request->validated();
        $validatedRequest["user_id"] = auth()->id(); // auth::id()
        $userTask = $userTask->addNewUserTask($validatedRequest);
        $userTask->user_id = null;
        return $this->api(new UserTaskResource($userTask->toArray()), __METHOD__);
    }

    public function isTaskDoneBefore($request): bool
    {
        $task =  UserTask::query()
        ->where("user_id",auth()->id())
        ->where("task_id",$request->task_id)
        ->exists();

        if($task)
           return false;

        return true;
    }


}
