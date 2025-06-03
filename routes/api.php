<?php

use Illuminate\Http\Request;
use App\Http\Middleware\Managment;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdminManager;
use App\Http\Controllers\V1\Admin\AdminController;
use App\Http\Controllers\V1\Admin\GameSettingController;



    Route::prefix("v1")->group(function () {

        Route::prefix("Admin")->group(function () { // admin apis



            Route::prefix("auth")->controller(App\Http\Controllers\V1\Admin\AuthController::class)->group(function(){
                Route::post("login","login");
                Route::post("logout","logout")->middleware(["auth:sanctum"]);
            });

            Route::middleware(["auth:sanctum",CheckAdmin::class])->group(function(){
                Route::post("deposit_check",[App\Http\Controllers\V1\Admin\CryptoCurrencyController::class,"transactionRequest"]);
                Route::apiResource('/admin', AdminController::class)
                ->middleware(CheckAdminManager::class); // Example route

                Route::post("token_expire",[App\Http\Controllers\V1\Admin\AuthController::class,"isTokenValied"]);
                route::apiResource("avatar",App\Http\Controllers\V1\Admin\AvatarController::class);
                Route::apiResource("badge",App\Http\Controllers\V1\Admin\BadgeController::class);
                Route::apiResource("badge-farm",App\Http\Controllers\V1\Admin\BadgeFarmsController::class);
                Route::apiResource("farm",App\Http\Controllers\V1\Admin\FarmController::class);
                Route::apiResource("mailbox",App\Http\Controllers\V1\Admin\MailboxController::class);
                Route::apiResource("task",App\Http\Controllers\V1\Admin\TasksController::class);
                Route::apiResource("transaction",App\Http\Controllers\V1\Admin\TransactionController::class);
                Route::apiResource("transfer",App\Http\Controllers\V1\Admin\TransferController::class);
                Route::apiResource("user-avatar",App\Http\Controllers\V1\Admin\UserAvatarController::class);
                Route::apiResource("user",App\Http\Controllers\V1\Admin\UserController::class);
                Route::apiResource("user-farm",App\Http\Controllers\V1\Admin\UserFarmsController::class);
                Route::apiResource("user-referral",App\Http\Controllers\V1\Admin\UserReferralController::class);
                Route::apiResource("user-referral-management",App\Http\Controllers\V1\Admin\UserReferralManagementController::class);
                Route::apiResource("user-referral-reward",App\Http\Controllers\V1\Admin\UserReferralRewardController::class);
                Route::apiResource("user-task",App\Http\Controllers\V1\Admin\UserTaskController::class);
                Route::apiResource("warehouse-level",App\Http\Controllers\V1\Admin\WarehouseLevelController::class);
                Route::apiResource("warehouse",App\Http\Controllers\V1\Admin\WarehouseController::class);
                Route::apiResource("giftcart",App\Http\Controllers\V1\Admin\GiftcartController::class);
                Route::apiResource("policy_and_rule",App\Http\Controllers\V1\Admin\PolicyAndRuleController::class);
                Route::apiResource("market_history",App\Http\Controllers\V1\Admin\MarketHistoryController::class);
                Route::apiResource('tutorial_message',App\Http\Controllers\V1\Admin\TutorialMessageController::class);
            });

        });

       // user-apis

        include  "user-api.php";
    });





