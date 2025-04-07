<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = [
        "title",
        "attachment_url",
        "gem_reward",
        "token_reward",
    ];


    public function addNewTasks( $request){
    return $this->query()->create( $request->validated());
    }

    public function updateTasks($request){
    $this->update($request->validated());
    return $this;
    }

    public function deleteTasks(){
    return $this->delete();
    }
}
