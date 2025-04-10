<?php

use Illuminate\Support\Facades\Route;


Route::prefix("user")->group(function() {

//    Route::post("first-auth", [App\Http\Controllers\V1\User\FirstAuthController::class, "firstStepLogin"]);
//    //auth
//    Route::post("second-auth", [App\Http\Controllers\V1\User\FirstAuthController::class, "secondStepLogin"]);

    // policy
    Route::get("policy",[App\Http\Controllers\V1\User\PoliciesController::class,"index"]); // all policy


    //task part
    Route::get("task/tasks", [App\Http\Controllers\V1\User\TasksController::class, "index"]); // all tasks
    Route::get("task/user_task",[App\Http\Controllers\V1\User\UserTasksController::class, "index"]); // user tasks
    Route::get("task/badge", [App\Http\Controllers\V1\User\UserBadgeController::class, "index"]); // user badge

    // map part
    Route::get("map",[App\Http\Controllers\V1\User\FarmController::class,"index"]); // all farms
    Route::get("map/user_farm",[App\Http\Controllers\V1\User\UserFarmController::class,"index"]); // user farms have
    Route::post("map/buy_farm",[App\Http\Controllers\V1\User\UserFarmController::class,"store"]); // buy farm by user



    Route::get("policies", [App\Http\Controllers\V1\User\PoliciesController::class, "index"]);
    Route::get("avatar", [App\Http\Controllers\V1\User\AvatarController::class, "index"]);
    Route::post("User_task", [App\Http\Controllers\V1\User\UserTasksController::class, "store"]);


});


