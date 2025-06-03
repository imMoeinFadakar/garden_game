<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = 
    [
        "address",
        "user_id"
    ];


    protected $hidden = ['user_id'];

    public function addNewWallet( $request){
    return $this->create( $request);
    }

  


}
