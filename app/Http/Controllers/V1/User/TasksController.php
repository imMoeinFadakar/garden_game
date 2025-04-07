<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\TaskResource;
use App\Models\Tasks;
use Illuminate\Http\Request;

class TasksController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = Tasks::query()
        ->orderBy("id")
        ->get();

        return $this->api(TaskResource::collection($task),__METHOD__);
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
