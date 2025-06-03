<?php

namespace App\Http\Controllers\V1\User;

use App\Actions\User\UseGiftCartAction;
use App\Http\Requests\V1\User\Giftcart\UseGiftCartRequest;
use App\Http\Resources\V1\User\GiftcartResource;


class GiftCartController extends BaseUserController
{   

   public function useGiftCartByUser(UseGiftCartRequest $request, UseGiftCartAction $useGiftCartAction)
    {
        $user = auth()->user();

        $newGemBalance = $useGiftCartAction->executeGiftcart($user, $request->code);

        return $this->api(
            new GiftCartResource(['gem_amount' => $newGemBalance]),
            __METHOD__,
            'Gift card applied successfully'
        );
    }



}
