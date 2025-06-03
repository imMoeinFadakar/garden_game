<?php

namespace App\Trait;

use Nette\Utils\Random;
use App\Models\Transaction;

trait RandomNumberTrait
{
       public function createTransactionNumber(): string
    {
        
        $number = Random::generate(15,'0-9');


        
        while($this->isTransactionExists($number)){
            
            $number = Random::generate(15,'0-9');
        }
        
        return $number;


    }
  

    protected function isTransactionExists($transactionNumber): bool
    {
        return Transaction::query()
        ->where('transaction_number',$transactionNumber)
        ->exists();

    }
}
