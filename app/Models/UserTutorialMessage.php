<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTutorialMessage extends Model
{
    protected $fillable =['user_id'];

    protected $hidden = ['user_id'];

    public function addNewUserTutorialMessage( $request){
    return $this->create( $request->validated());
    }

}
