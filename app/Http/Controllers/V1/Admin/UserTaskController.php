<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Resources\V1\Admin\UserTaskResource;
use App\Models\UserTask;
use Illuminate\Http\Request;

class UserTaskController extends BaseAdminController
{
    /**
     * usertask/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $UserTasks = UserTask::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query) => $query->where("id", $request->id))
        ->when(isset($request->user_id),fn($query) => $query->where("user_id", $request->user_id))
        ->when(isset($request->task_id),fn($query) => $query->where("task_id", $request->task_id))
        ->with(['user:id,name,username','task:id,title'])
        ->get();

        return $this->api(UserTaskResource::collection($UserTasks),__METHOD__);
    }
    /**
     * usertask/show
     * @param \App\Models\UserTask $userTask
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(UserTask $userTask)
    {
        return $this->api(new UserTaskResource($userTask->toArray()),__METHOD__);
    }
}
