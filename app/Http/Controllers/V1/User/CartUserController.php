<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\CartUserResource;
use App\Models\CartUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartUserController extends BaseUserController
{
    public function getUserCart()
    {
        $userId = auth()->id();
        $cacheKey = "user_cart_{$userId}";

        $userCart = cache()->remember($cacheKey, now()->addYear(), function () use ($userId) {
            return CartUser::query()
                ->where("user_id", $userId)
                ->get(['cart_number', 'expire_date', 'cvv']);
        });

        return $this->api(CartUserResource::collection($userCart), __METHOD__);
    }

    public function createNewCart(Request $request)
    {
        if (auth()->user()->cart()->count() >= 1) {
            return $this->api(null, __METHOD__, 'you already own this cart');
        }

        $newCartNumber = $this->generateCardNumber();
        $newCvv2Number = $this->newCvv2Code();

        $newUserCart = $this->insertCartNumber($newCartNumber, $newCvv2Number);

      
        cache()->forget("user_cart_" . auth()->id());

        return $this->api(new CartUserResource($newUserCart), __METHOD__);
    }

    public function generateCardNumber()
    {
        $prefix = '68188118';
        $uniquePart = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        $cardNumber = $prefix . $uniquePart;

        while (CartUser::where('cart_number', $cardNumber)->exists()) {
            $uniquePart = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $cardNumber = $prefix . $uniquePart;
        }

        return $cardNumber;
    }

    public function newCvv2Code()
    {
        $NewCvv2Code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        while (CartUser::where('cvv', $NewCvv2Code)->exists()) {
            $NewCvv2Code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }

        return intval($NewCvv2Code);
    }

    public function insertCartNumber($cartNumber, $newCVV2)
    {
        $newUserCart = CartUser::query()->create([
            "cart_number" => $cartNumber,
            "user_id" => auth()->id(),
            'expire_date' => Carbon::now()->addYears(2),
            'cvv' => $newCVV2
        ]);

        return $newUserCart->only(['cart_number', 'expire_date', 'cvv']);
    }

    public function HasUserCart()
    {
        return CartUser::query()
            ->where('user_id', auth()->id())
            ->exists();
    }
}

