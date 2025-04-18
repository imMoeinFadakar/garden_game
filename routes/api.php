<?php

use App\Http\Middleware\CheckAdminManager;
use App\Http\Middleware\Managment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Admin\AdminController;



    Route::prefix("v1")->group(function () {

        Route::prefix("Admin")->group(function () {

            Route::prefix("auth")->controller(App\Http\Controllers\V1\Admin\AuthController::class)->group(function(){
                Route::post("login","login");
                Route::post("logout","logout")->middleware(["auth:sanctum"]);
            });

            Route::post("deposit_check",[App\Http\Controllers\V1\Admin\CryptoCurrencyController::class,"transactionRequest"]);

            Route::middleware(["auth:sanctum"])->group(function(){

                Route::apiResource('/admin', AdminController::class)
                ->middleware(CheckAdminManager::class); // Example route
                route::apiResource("avatar",App\Http\Controllers\V1\Admin\AvatarController::class);
                Route::apiResource("badge",App\Http\Controllers\V1\Admin\BadgeController::class);
                Route::apiResource("badge-farm",App\Http\Controllers\V1\Admin\BadgeFarmsController::class);
                Route::apiResource("farm",App\Http\Controllers\V1\Admin\FarmController::class);
                Route::apiResource("mailbox",App\Http\Controllers\V1\Admin\MailboxController::class);
                Route::apiResource("product",App\Http\Controllers\V1\Admin\ProductController::class);
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
                Route::apiResource("wallet",App\Http\Controllers\V1\Admin\WalletController::class);
                Route::apiResource("warehouse-product",App\Http\Controllers\V1\Admin\WarehouseProductController::class);
                Route::apiResource("warehouse-level",App\Http\Controllers\V1\Admin\WarehouseLevelController::class);
                Route::apiResource("warehouse",App\Http\Controllers\V1\Admin\WarehouseController::class);
                Route::apiResource("giftcart",App\Http\Controllers\V1\Admin\GiftcartController::class);
                Route::apiResource("game_setting",App\Http\Controllers\V1\Admin\GameSettingController::class);
                Route::apiResource("policy",App\Http\Controllers\V1\Admin\PolicyController::class);
                Route::post("token_expire",[App\Http\Controllers\V1\Admin\AuthController::class,"isTokenValied"]);
            });

        });

        // user-apis

        include  "user-api.php";
    });





