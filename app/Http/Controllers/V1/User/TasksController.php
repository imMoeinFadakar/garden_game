<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\Tasks\StoreUserTaskRequest;
use App\Http\Requests\V1\User\UserTask\UserTaskRequest;
use App\Http\Resources\V1\User\TaskResource;
use App\Models\Tasks;
use App\Models\UserTask;
use App\Trait\UserActiveTrait;
use Cache;
use Illuminate\Http\Request;

class TasksController extends BaseUserController
{   
   
    /**
     * get all tasks
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllTask()
    {   

        

        $cacheKey = "all_task";

        $AllTask = Cache::remember($cacheKey,
        now()->addDay(),function(){

            return Tasks::all();
        
        });

        return $this->api(TaskResource::collection($AllTask),__METHOD__);
    }

}
