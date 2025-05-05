<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Requests\V1\User\Wallet\DeleteWalletRequest;
use App\Http\Requests\V1\User\Wallet\StoreWalletRequest;
use App\Http\Resources\V1\User\WalletResource;
use App\Models\AdderssUser;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\WithdrawalResource;

class WalletController extends BaseUserController
{
    public function getUserwallet(Request $request)
    {
        $wallet = Wallet::query()
        ->where("user_id",auth()->id())
        ->get(['id','address','created_at']);

        return $this->api(WithdrawalResource::collection($wallet->toArray()),__METHOD__);
    }

    public function newUserwallet(StoreWalletRequest $request , Wallet $wallet)
    {
        $userWallets = $this->userWalletCount();

        $hasUserWallet = $this->maxUserWallet($userWallets);
        if(! $hasUserWallet)
            return $this->api(null,
                        __METHOD__,
                        'you reached your maximum wallet number');


        $newAddressUser = 
        [
            "user_id" => auth()->id(),
            "address" => $request->address
        ];


       $newWallet =  $wallet->addNewWallet($newAddressUser);
        $newWallet->user_id = null;
        return $this->api(new Wallet($newWallet->toArray()),__METHOD__);
    }
    public function userWalletCount(): int
    {
        return Wallet::query()
        ->where('user_id',auth()->id())
        ->count();
    }

    public function maxUserWallet(int $userWalletCount): bool
    {
        
        if($userWalletCount >= 5)
            return false;


        return true;


    }


    public function deleteUserWallet(DeleteWalletRequest $request,Wallet $wallet)
    {
        
        $wallet->query()->find($request->wallet_id)->delete();

        return $this->api(null,__METHOD__,'deleted successfully');

    }


}
