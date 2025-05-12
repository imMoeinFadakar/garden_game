<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\CartUserResource;
use App\Http\Resources\V1\User\UserCartResource;
use App\Models\CartUser;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartUserController extends BaseUserController
{

    public function getUserCart()
    {
        $userCart = CartUser::query()
        ->where("user_id",auth()->id())
        ->get(['cart_number','expire_date','cvv']);

        return $this->api(new CartUserResource($userCart),__METHOD__);

    }



      public function createNewCart(Request $request)
    {
        $userCart = $this->HasUserCart(); // check has user cart

        if($userCart)
            return $this->api(null,409,'you already own this cart');

        $newCart = $this->generateCardNumber(); // generate new unique cart number

        $newCvv2 = $this->newCvv2Code(); // generate new cvv2 number 

        $newUserCart = $this->insertCartNumber($newCart,$newCvv2); // insert new data

        return $this->api(new CartUserResource($newUserCart),__METHOD__);
    }


    public function generateCardNumber()
    {
        $prefix = '68188118'; // 8 رقم ثابت
         $uniquePart = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT); // 8 رقم تصادفی
        $cardNumber = $prefix . $uniquePart;

        // اطمینان از اینکه شماره کارت یونیک باشد
         while (CartUser::where('cart_number', $cardNumber)->exists()) {
        $uniquePart = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        $cardNumber = $prefix . $uniquePart;
         }

        return $cardNumber;
    }

    public function newCvv2Code()
    {
        
         $NewCvv2Code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 8 رقم تصادفی
        

        // اطمینان از اینکه شماره کارت یونیک باشد
         while (CartUser::where('cvv', $NewCvv2Code)->exists()) {
       
            $NewCvv2Code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 8 رقم تصادفی

         }

        return intval($NewCvv2Code);
    }


    public function insertCartNumber($cartNumber,$newCVV2)
    {
        
        $newUserCart =  CartUser::query()
        ->create([
            "cart_number" => $cartNumber,
            "user_id" => auth()->id(),
            'expire_date' => Carbon::now()->addYears(2),
            'cvv' => $newCVV2
        ]);


        return  $newUserCart->only(['cart_number','expire_date','cvv']);


    }


  

    public function HasUserCart()
    {
        return CartUser::query()
        ->where('user_id',auth()->id())
        ->exists();
    }



}
