<?php

use Illuminate\Support\Facades\Route;


Route::prefix("user")->group(function(){

     Route::post("first-auth",[App\Http\Controllers\V1\User\FirstAuthController::class,"firstStepLogin"]);

     Route::middleware("api_user")->group(function (){

         Route::post("second-auth",[App\Http\Controllers\V1\User\FirstAuthController::class , "secondStepLogin"]);
         Route::get("policies",[App\Http\Controllers\V1\User\PoliciesController::class,"index"]);
         Route::get("avatar",[App\Http\Controllers\V1\User\AvatarController::class,"index"]);
         Route::get("tasks",[App\Http\Controllers\V1\User\TasksController::class,"index"]);
         Route::post("User_task",[App\Http\Controllers\V1\User\UserTasksController::class,"store"]);




     });

});

