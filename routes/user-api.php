<?php

use App\Http\Middleware\player;
use Illuminate\Support\Facades\Route;


Route::prefix("user")->group(function() {



    Route::post("auth_user",[App\Http\Controllers\V1\User\FirstAuthController::class,"userLogin"]);
    Route::get("policy",[App\Http\Controllers\V1\User\PoliciesController::class,"index"]); // all policy

    Route::middleware(['auth:sanctum','auth'])->group(function(){

        Route::post("new_referral",[App\Http\Controllers\V1\User\UserController::class,"newReferral"]);
        Route::post("find_user",[App\Http\Controllers\V1\User\UserController::class,"findingUser"]);
        Route::post("register",[App\Http\Controllers\V1\User\RegistrationController::class,"register"]);
        Route::get("tasks", [App\Http\Controllers\V1\User\TasksController::class, "index"]); // all tasks
        Route::get("user_task",[App\Http\Controllers\V1\User\UserTasksController::class, "index"]); // user tasks
        Route::get("badge", [App\Http\Controllers\V1\User\UserBadgeController::class, "index"]); // user badge
        Route::get("farm",[App\Http\Controllers\V1\User\FarmController::class,"index"]); // all farms
        Route::get("user_farm",[App\Http\Controllers\V1\User\UserFarmController::class,"index"]); // user farms have
        Route::get("user",[App\Http\Controllers\V1\User\UserController::class,"index"]); // get self user
        Route::post("user_task", [App\Http\Controllers\V1\User\UserTasksController::class, "store"]);
        Route::post("buy_farm",[App\Http\Controllers\V1\User\BuyFarmController::class,"store"]);
        Route::post("new_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"create"]);
        Route::get("avatar", [App\Http\Controllers\V1\User\AvatarController::class, "index"]);
        Route::post("user_avatar",[App\Http\Controllers\V1\User\UserAvatarController::class,"store"]);
        Route::get("warehouse",[App\Http\Controllers\V1\User\WarehouseProductController::class,"index"]);
        Route::post("withdrawal",[App\Http\Controllers\V1\User\WithdrawalControler::class,"withdrawal"]);
        Route::get("transaction",[App\Http\Controllers\V1\User\WithdrawalControler::class,"index"]);
        Route::post("use_giftcart",[App\Http\Controllers\V1\User\GiftCartController::class,"useGiftCart"]);
        Route::post("active_warehouse",[App\Http\Controllers\V1\User\UserStatusController::class,"activeWarehouse"]);
        Route::post("active_market",[App\Http\Controllers\V1\User\UserStatusController::class,"activeMarket"]);
        Route::post("user_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"store"]);

        
        Route::post('pay_reward',[App\Http\Controllers\V1\User\PayRequestControler::class,"newPayingRequest"]);
        Route::get("get_user_avatar",[App\Http\Controllers\V1\User\UserAvatarController::class,"index"]);
        Route::post("sell_product",[App\Http\Controllers\V1\User\MarketController::class,"sellProduct"]);

    });
 
  


    // Route::get("get_gen_one_reffral",[App\Http\Controllers\V1\User\UserReferralController::class,"index"]);
    // Route::get("warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"index"]);
   

    // fix later

    // Route::post("update_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"store"]);
    // Route::post("transfer",[App\Http\Controllers\V1\User\TransferController::class,"store"]);
    // Route::post("new_product",[App\Http\Controllers\V1\User\WarehouseProductController::class,"store"]); // save new product

 

   

});


