<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Requests\V1\Admin\Task\StoreTaskRequest;
use App\Http\Requests\V1\Admin\Task\UpdateTaskRequest;
use App\Http\Resources\V1\Admin\TaskResource;
use App\Models\Tasks;
use App\Trait\DeleteCacheTrait;
use Illuminate\Http\Request;

class TasksController extends BaseAdminController
{   
    use DeleteCacheTrait;
    /**
     * task/index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $tasks = Tasks::query()
        ->orderBy("id")
        ->when(isset($request->id),fn($query) =>  $query->where("id", $request->id))
        ->when(isset($request->title),fn($query) =>  $query->where("title", $request->title))
        ->when(isset($request->gem_reward),fn($query) =>  $query->where("gem_reward", $request->gem_reward))
        ->when(isset($request->token_reward),fn($query) =>  $query->where("token_reward", $request->token_reward))
        ->get();

        return $this->api(new TaskResource($tasks->toArray()),__METHOD__);
    }

    /**
     * task/store
     * @param \App\Http\Requests\V1\Admin\Task\StoreTaskRequest $request
     * @param \App\Models\Tasks $tasks
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request, Tasks $tasks)
    {
       $tasks = $tasks->addNewTasks($request);
       $this->deleteCache("all_task");
       return $this->api(new TaskResource($tasks->toArray()),__METHOD__);
    }
    /**
     * task/show
     * @param \App\Models\Tasks $tasks
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Tasks $task)
    {
        return $this->api(new TaskResource($task->toArray()),__METHOD__);

    }

    /**
     * task/update
     * @param \App\Http\Requests\V1\Admin\Task\UpdateTaskRequest $request
     * @param \App\Models\Tasks $tasks
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, Tasks $task)
    {
        $task->updateTasks($request);
        $this->deleteCache("all_task");
        return $this->api(new TaskResource($task->toArray()),__METHOD__);
    }

    /**
     * task/destroy
     * @param \App\Models\Tasks $task
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Tasks $task)
    {
        $task->deleteTasks();
        $this->deleteCache("all_task");
        return $this->api(new TaskResource($task->toArray()),__METHOD__);

    }
}
