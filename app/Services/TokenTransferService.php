<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class TokenTransferService
{
    /**
     * Execute the token transfer between two users.
     *
     * @param User $sender
     * @param User $receiver
     * @param int $amount
     * @param array $metaData
     * @return Transfer|null
     * @throws \Throwable
     */
    public function transfer(User $sender, User $receiver, int $amount, array $metaData = []): ?Transfer
    {
        return DB::transaction(function () use ($sender, $receiver, $amount, $metaData) {
            // Update balances
            $sender->decrement('token_amount', $amount);
            $receiver->increment('token_amount', $amount);

            // Create transfer record
            return Transfer::create([
                'from_user' => $sender->id,
                'to_user' => $receiver->id,
                'token_amount' => $amount,
                ...$metaData,
            ]);
        });
    }
}





