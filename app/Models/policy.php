<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{   

    protected $fillable = [
        "text"
    ];

    public function addNewPolicy( $request){
    return $this->create( $request->validated());
    }


    public function updatePolicy($request){
    $this->update($request->validated());
    return $this;
    }


    public function deletePolicy(){
    return $this->delete();
    }

}
