<?php

use Illuminate\Support\Facades\Route;


Route::prefix("user")->group(function() {

//    Route::post("first-auth", [App\Http\Controllers\V1\User\FirstAuthController::class, "firstStepLogin"]);
//    //auth
//    Route::post("second-auth", [App\Http\Controllers\V1\User\FirstAuthController::class, "secondStepLogin"]);

    // policy
    Route::get("policy",[App\Http\Controllers\V1\User\PoliciesController::class,"index"]); // all policy


    //task part
    Route::get("tasks", [App\Http\Controllers\V1\User\TasksController::class, "index"]); // all tasks
    Route::get("user_task",[App\Http\Controllers\V1\User\UserTasksController::class, "index"]); // user tasks
    Route::get("badge", [App\Http\Controllers\V1\User\UserBadgeController::class, "index"]); // user badge
    


    // map part
    Route::get("farm",[App\Http\Controllers\V1\User\FarmController::class,"index"]); // all farms
    Route::get("user_farm",[App\Http\Controllers\V1\User\UserFarmController::class,"index"]); // user farms have

    ///////
    Route::post("buy_farm",[App\Http\Controllers\V1\User\BuyFarmController::class,"store"]);
    ///////

//    Route::post
    // Route::get("policies", [App\Http\Controllers\V1\User\PoliciesController::class, "index"]);
    Route::get("avatar", [App\Http\Controllers\V1\User\AvatarController::class, "index"]);
    Route::post("user_avatar",[App\Http\Controllers\V1\User\UserAvatarController::class,"store"]);
    Route::post("user_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"store"]);
    Route::post("user_task", [App\Http\Controllers\V1\User\UserTasksController::class, "store"]);

    //get user warehouse products
    Route::get("warehouse",[App\Http\Controllers\V1\User\WarehouseProductController::class,"index"]);
    Route::post("new_product",[App\Http\Controllers\V1\User\WarehouseProductController::class,"store"]);

    // warehouse

    Route::get("warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"index"]);
    Route::post("update_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"store"]);

    Route::get("user_referral",[App\Http\Controllers\V1\User\UserReferralController::class,"index"]);
    Route::post("new_user_referral",[App\Http\Controllers\V1\User\UserReferralController::class,"store"]);

    Route::post("active_warehouse",[App\Http\Controllers\V1\User\UserStatusController::class,"activeWarehouse"]);
    Route::post("active_market",[App\Http\Controllers\V1\User\UserStatusController::class,"activeMarket"]);

    Route::post("transfer",[App\Http\Controllers\V1\User\TransferController::class,"store"]);
    // giftcart
    Route::post("use_giftcart",[App\Http\Controllers\V1\User\GiftCartController::class,"useGiftCart"]);

});


