<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\Tasks\StoreUserTaskRequest;
use App\Http\Requests\V1\User\UserTask\UserTaskRequest;
use App\Http\Resources\V1\User\TaskResource;
use App\Models\Tasks;
use App\Models\UserTask;
use Illuminate\Http\Request;

class TasksController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AllTask = Tasks::query()
        ->orderBy("id")
        ->get();

        return $this->api(TaskResource::collection($AllTask),__METHOD__);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // not exists
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // not exists
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // not exists
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // not exists
    }
}
