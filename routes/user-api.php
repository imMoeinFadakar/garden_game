<?php

use App\Http\Middleware\CheckUserStatus;
use App\Http\Middleware\player;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\User\FarmController;


Route::prefix("user")->group(function() {



    Route::post("auth_user",[App\Http\Controllers\V1\User\FirstAuthController::class,"loginUserByTelegramId"]); // first login
    Route::get("policy",[App\Http\Controllers\V1\User\PoliciesController::class,"getAllPolicy"]); // all policy

    Route::middleware(['auth:sanctum','auth','check.user.status'])->group(function(){

        // auth 
        Route::post("find_user",[App\Http\Controllers\V1\User\UserController::class,"findUserByTelegarmId"]); // find user by TL id
        Route::get("user",[App\Http\Controllers\V1\User\UserController::class,"getAuthUser"]); // get self user
        Route::get("get_user_avatar",[App\Http\Controllers\V1\User\UserAvatarController::class,"getUserAvatar"]); // find user avatar
        Route::post("new_referral",[App\Http\Controllers\V1\User\UserController::class,"addNewReferral"]); // add new referral 
        Route::post("register",[App\Http\Controllers\V1\User\RegistrationController::class,"addUsernameAndGender"]);

        // task 
        Route::get("tasks", [App\Http\Controllers\V1\User\TasksController::class, "getAllTask"]); // all tasks
        Route::get("user_task",[App\Http\Controllers\V1\User\UserTasksController::class, "getAllUserTasks"]); // user tasks
        Route::post("user_task", [App\Http\Controllers\V1\User\UserTasksController::class, "addCompletedTask"]);

        //badge
        Route::get("badge", [App\Http\Controllers\V1\User\UserBadgeController::class, "getUserBadges"]); // user badge

        // farm
        Route::get("farm",[App\Http\Controllers\V1\User\FarmController::class,"getAllExistsFarmInGame"]); // all farms
        Route::get("user_farm",[App\Http\Controllers\V1\User\UserFarmController::class,"getAllUserFarm"]); // user farms have
        Route::post("buy_farm",[App\Http\Controllers\V1\User\BuyFarmController::class,"buyFarmByUser"]);
        Route::get('show_farm/{id}', [FarmController::class, 'show']);

        // warehouse 
        Route::post("new_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"createNewWarehouse"]);
        Route::get("warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"getAllUserwarehouse"]);

        Route::post("user_warehouse",[App\Http\Controllers\V1\User\WarehouseController::class,"updateUserWarehouse"]);
        Route::post('add_prodcut',[App\Http\Controllers\V1\User\WarehouseController::class,"addNewProduct"]);
     
        // avatar 
        Route::post("user_avatar",[App\Http\Controllers\V1\User\UserAvatarController::class,"addNewAvatarForUser"]);
        Route::get("avatar", [App\Http\Controllers\V1\User\AvatarController::class, "getAllAvatarInGame"]);

        //wallet 
        Route::get("get_user_wallet",[App\Http\Controllers\V1\User\WalletController::class,"getUserwallet"]);
        Route::post("add_user_wallet",[App\Http\Controllers\V1\User\WalletController::class,"newUserwallet"]);
        Route::post("delete_wallet",[App\Http\Controllers\V1\User\WalletController::class,"deleteUserWallet"]);


        // transaction
        Route::post("withdrawal",[App\Http\Controllers\V1\User\WithdrawalControler::class,"addNewWithdrawalRequest"]);
        Route::post("use_giftcart",[App\Http\Controllers\V1\User\GiftCartController::class,"useGiftCartByUser"]);
        Route::post('pay_reward',[App\Http\Controllers\V1\User\PayRequestControler::class,"newPayingRequest"]);
        Route::post("transfer",[App\Http\Controllers\V1\User\TransferController::class,"transferTokenByUser"]);
        Route::post('new_exchange',[App\Http\Controllers\V1\User\ExchangeController::class,"exchangeTokenToGem"]);

        Route::get("user_withdraw",[App\Http\Controllers\V1\User\WithdrawalControler::class,"getUserWithdrawHistory"]);
        Route::get("transaction",[App\Http\Controllers\V1\User\WithdrawalControler::class,"getUserTransactionHistory"]);
        Route::get("user_referral_reward",[App\Http\Controllers\V1\User\PayRequestControler::class,'getUserReferralReward']);
       
        Route::get('all_transfer',[App\Http\Controllers\V1\User\TransferController::class,"sendTransfer"]); // send
        Route::get('my_transfer',[App\Http\Controllers\V1\User\TransferController::class,"receiveTransfer"]); // recive
       
        Route::get('my_exchange',[App\Http\Controllers\V1\User\ExchangeController::class,"UserExchangeHistory"]);


        // activate
        Route::post("active_warehouse",[App\Http\Controllers\V1\User\UserStatusController::class,"activeWarehouse"]);
        Route::post("active_market",[App\Http\Controllers\V1\User\UserStatusController::class,"activeMarket"]);
      
        // market
        Route::post("sell_product",[App\Http\Controllers\V1\User\MarketController::class,"sellProduct"]);
        Route::get("sell_product_history",[App\Http\Controllers\V1\User\MarketController::class,"getUserMarketHistory"]);

  
        //user 
        Route::post('find_recipient',[App\Http\Controllers\V1\User\UserController::class,"findUserByTransferCartNumber"]);
        
        //user transaction cart
        Route::post('new_cart',[App\Http\Controllers\V1\User\CartUserController::class,'createNewCart']);
        Route::get("user_cart",[App\Http\Controllers\V1\User\CartUserController::class,'getUserCart']);
        
        // tutorial message
        Route::get('tutorial_message',[App\Http\Controllers\V1\User\TutorialMessageController::class, "getTutorialMessage"]);
        Route::post('Add_user_done_tutorial',[App\Http\Controllers\V1\User\TutorialMessageController::class, "addNewUserToDoneList"]);
        Route::get('user_done_tutorial',[App\Http\Controllers\V1\User\TutorialMessageController::class, "isUserDoneWithTutorial"]);

        // teammate
        Route::get('team',[App\Http\Controllers\V1\User\TeamManagmentController::class,"getAllUserReferralQuentity"]);
        Route::get('all_teammate_request',[App\Http\Controllers\V1\User\TeammateRequestController::class,"getAllTeammateRequestCount"]);
        Route::post('new_teammate_request',[App\Http\Controllers\V1\User\TeammateRequestController::class,"addNewTeammateRequest"]);
    });
 

   

});


