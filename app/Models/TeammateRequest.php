<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeammateRequest extends Model
{
    protected $fillable = ['user_id','status'];

    public function addNewTeammateRequest(array $request){
        return $this->create($request);
    }


    

    public function updateTeammateRequest($request){
        $this->update($request->validated());
    return $this;
    }

    public function delete(): ?bool 
    {
    return $this->delete();
    }


}
