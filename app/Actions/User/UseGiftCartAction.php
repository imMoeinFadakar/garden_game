<?php 

namespace App\Actions\User;

use App\Models\Giftcart;
use App\Models\Transaction;
use App\Models\User;
use App\Trait\RandomNumberTrait;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Random;
use Str;


class UseGiftCartAction{

    use RandomNumberTrait;
    public function executeGiftcart(User $user, string $code)
    {
        return DB::transaction(function() use($user,$code){

            $giftcard = Giftcart::query()
            ->where('code',$code)
            ->firstOrfail();

            $giftcard->delete();

            $user->increment('gem_amount',$giftcard->value);

            $transactionNumber = $this->createTransactionNumber();


            Transaction::query()
            ->create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $giftcard->value,
                'status' => 'done',
                'transaction_number' => intval($transactionNumber)
            ]);

            return $user->gem_amount;

        });

    }

 

}



