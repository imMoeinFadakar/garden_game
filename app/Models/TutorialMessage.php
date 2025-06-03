<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorialMessage extends Model
{
    
    protected $fillable = [
        'message',
        'page'
    ];

  


    public function addNewTutorialMessage( $request){
    return $this->create( $request->validated());
    }

    public function updateTutorialMessage($request){
    $this->update($request->validated());
    return $this;
    }

    public function deleteTutorialMessage(){
    return $this->delete();
    }
}
