<?php

use App\Http\Middleware\player;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\User\FarmController;


Route::prefix("user")->group(function() {



    Route::post("auth_user",[App\Http\Controllers\V1\User\FirstAuthController::class,"userLogin"]); // first login
    Route::get("policy",[App\Http\Controllers\V1\User\PoliciesController::class,"index"]); // all policy

    Route::middleware(['auth:sanctum','auth'])->group(function(){

    
        // auth 
        Route::post("find_user",[App\Http\Controllers\V1\User\UserController::class,"findingUser"]); // find user by TL id
        Route::get("user",[App\Http\Controllers\V1\User\UserController::class,"index"]); // get self user
        Route::get("get_user_avatar",[App\Http\Controllers\V1\User\UserAvatarController::class,"index"]); // find user avatar
        Route::post("new_referral",[App\Http\Controllers\V1\User\UserController::class,"newReferral"]); // add new referral 
        Route::post("register",[App\Http\Controllers\V1\User\RegistrationController::class,"register"]);

        // task 
        Route::get("tasks", [App\Http\Controllers\V1\User\TasksController::class, "index"]); // all tasks
        Route::get("user_task",[App\Http\Controllers\V1\User\UserTasksController::class, "index"]); // user tasks
        Route::post("user_task", [App\Http\Controllers\V1\User\UserTasksController::class, "store"]);

        //badge
        Route::get("badge", [App\Http\Controllers\V1\User\UserBadgeController::class, "index"]); // user badge

        // farm
        Route::get("farm",[App\Http\Controllers\V1\User\FarmController::class,"index"]); // all farms
        Route::get("user_farm",[App\Http\Controllers\V1\User\UserFarmController::class,"index"]); // user farms have
        Route::post("buy_farm",[App\Http\Controllers\V1\User\BuyFarmController::class,"store"]);
        Route::get('show_farm/{id}', [FarmController::class, 'show']);


        // warehouse 
        Route::post("new_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"create"]);
        Route::get("warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"index"]);
        Route::post("user_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"store"]);
        Route::post('add_prodcut',[App\Http\Controllers\V1\User\WarehouseController::class,"storeProduct"]);



        // avatar 
        Route::post("user_avatar",[App\Http\Controllers\V1\User\UserAvatarController::class,"store"]);
        Route::get("avatar", [App\Http\Controllers\V1\User\AvatarController::class, "index"]);


        // transaction
        Route::post("withdrawal",[App\Http\Controllers\V1\User\WithdrawalControler::class,"withdrawal"]);
        Route::get("transaction",[App\Http\Controllers\V1\User\WithdrawalControler::class,"index"]);
        Route::post("use_giftcart",[App\Http\Controllers\V1\User\GiftCartController::class,"useGiftCart"]);
        Route::post('pay_reward',[App\Http\Controllers\V1\User\PayRequestControler::class,"newPayingRequest"]);
        Route::get("user_referral_reward",[App\Http\Controllers\V1\User\PayRequestControler::class,'index']);
        Route::post("transfer",[App\Http\Controllers\V1\User\TransferController::class,"store"]);
      
        // activate
        Route::post("active_warehouse",[App\Http\Controllers\V1\User\UserStatusController::class,"activeWarehouse"]);
        Route::post("active_market",[App\Http\Controllers\V1\User\UserStatusController::class,"activeMarket"]);
      
        // market
        Route::post("sell_product",[App\Http\Controllers\V1\User\MarketController::class,"sellProduct"]);
        Route::get("sell_product_history",[App\Http\Controllers\V1\User\MarketController::class,"userMarketHistory"]);



    });
 

   

});


