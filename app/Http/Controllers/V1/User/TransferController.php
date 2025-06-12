<?php

namespace App\Http\Controllers\V1\User;

use App\Models\User;
use App\Models\CartUser;
use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Trait\RandomNumberTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\V1\User\TransferResource;


class TransferController extends BaseUserController
{      
    use RandomNumberTrait;
public function receiveTransfer(Request $request)
{
    return $this->getUserTransfers('to_user', 'receive', $request);
}

public function sendTransfer(Request $request)
{
    return $this->getUserTransfers('from_user', 'send', $request);
}

private function getUserTransfers(string $userField, string $type, Request $request)
{
    $cacheKey = "user_{$type}_transfers_" . auth()->id();

    // پاک‌سازی کش برای اطمینان از دریافت آخرین اطلاعات
    Cache::forget($cacheKey);

    $transfers = Cache::remember($cacheKey, 300, function () use ($userField, $type, $request) {
        return Transfer::query()
            ->where($userField, auth()->id())
            ->when($request->filled('id'), fn($query) => $query->where('id', $request->id))
            ->when($request->filled('token_amount'), fn($query) => $query->where('token_amount', $request->token_amount))
            ->get(['id', 'token_amount', 'created_at'])
            ->each(function ($transfer) use ($type) {
                $transfer->setAttribute('type', $type);
            });
    });

    return $this->api(TransferResource::collection($transfers->toArray()), __METHOD__);
}


    /**
     * transfer from user to an other user
     * @param \App\Http\Requests\V1\User\Transfer\TransferRequest $request
     * @param \App\Models\Transfer $transfer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function transferTokenByUser(\App\Http\Requests\V1\User\Transfer\TransferRequest $request,Transfer $transfer)
    {
  $validatedRequest = $request->validated();
    $user = auth()->user();

 
    if ($user->cart()->value('cvv') !== intval($request->cvv2)) {
        return $this->api(null, __METHOD__, 'the cvv2 number is wrong!');
    }

   
    $minToken = 1050;
    $newAmount = $user->token_amount - $validatedRequest["token_amount"];

    if ($newAmount < $minToken) {
        return $this->api(null,__METHOD__,'you cant transfer while your balance is under ' . $minToken);
    }

    
    if (!$this->hasUserEnoughToken($user->token_amount, $validatedRequest["token_amount"])) {
        return $this->api(null, __METHOD__, 'you dont have enough token');
    }

   
    $receiverUser = $this->reciverWallet($validatedRequest['user_address']);
    if (!$receiverUser) {
        return $this->api(null,__METHOD__,'Receiver not found!');
    }


    if ($receiverUser->referral_code === $user->referral_code) {
        return $this->api(null, __METHOD__, 'you cant transfer to your wallet');
    }

    try {
        DB::beginTransaction();

        
        $minusToken = $this->minusUserToken($validatedRequest["token_amount"], $user);
        if (!$minusToken) {
            DB::rollBack();
            return $this->api(null,__METHOD__,"Failed to deduct token from sender");
        }

       
        $addToken = $this->AddUserReceiverToken($receiverUser, $validatedRequest["token_amount"]);
        if (!$addToken) {
            DB::rollBack();
            return $this->api(null,__METHOD__,"Failed to add token to receiver");
        }
        
        $transactionNumber = $this->createTransactionNumber();
        
        $validatedRequest["from_user"] = $user->id;
        $validatedRequest["to_user"] = $receiverUser->id;
        $validatedRequest["transaction_number"] = intval($transactionNumber);
        $transfer = $transfer->addNewTransfer($validatedRequest);

        DB::commit();


        Cache::forget(  "get_user_recived_token_" . auth()->id());
        Cache::forget(  "get_user_send_token_" . auth()->id());


        return $this->api(["tranfer_amount" => $transfer->token_amount], __METHOD__);
    } catch (\Exception $e) {
        DB::rollBack();
        return $this->errorResponse("Operation failed! Tokens have been returned.". $e->getMessage());
    }
    }

    /**
     * find reciver user
     * @param mixed $referalCode
     * @return User|null
     */
    public function reciverWallet($cartNumber)
    {
         $cartUser = $this->findUserByCartUser($cartNumber);

        return User::query()
        ->where("id",$cartUser->user_id)
        ->first();
    }

    public function findUserByCartUser($cartNumber)
    {
        return  CartUser::query()
        ->where('cart_number',$cartNumber)
        ->first();
    }

    /**
     * add token to reciver wallet
     * @param mixed $receiverWallet
     * @param int $amount
     */
    public function AddUserReceiverToken($receiver,int $amount)
    {
        $receiver->token_amount += $amount;
        return $receiver->save();
    }
    /**
     * minus user token
     * @param mixed $amount
     * @param mixed $user
     */
    public function minusUserToken($amount,$user)
    {   
        $user->token_amount -= $amount;
        return $user->save();
    }

    /**
     * Summary of findUserWallet
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function findUserWallet()
    {
        return auth()->user();
    }

    /**
     * check user is enough token
     * @param int $userGem
     * @param int $amount
     * @return bool
     */
    public function hasUserEnoughToken(int $userGem,int $amount)
    {
        if($userGem < $amount)
            return false;

        return true;
    }

}
