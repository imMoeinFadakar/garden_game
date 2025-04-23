<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Giftcart extends Model
{
    protected $fillable = [
        "value",
        "code"
    ];

    public function addNewGiftcart( $request,$GiftcartNumber){
       
        $codes=[];

        while (count($codes) < $GiftcartNumber) {  
            $uuid = (string) Str::uuid(); // Generate a UUID  

            // Check for uniqueness  
            if (!in_array($uuid, $codes)) {  
                $codes[] = $uuid; // Add to array if unique  
                $this->create(["value"=>$request->value,"code"=>$uuid]);
            }  

        }  
        
      
        return "done";
    }


    public function deleteGiftcart()
    {
        return $this->delete();
    }


}
