<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyAndRule extends Model
{
    protected $fillable =[
        "text"
    ];

    public function addNewPolicyAndRule( $request){
    return $this->create( $request->validated());
    }

    public function updatePolicyAndRule($request){
    $this->update($request->validated());
    return $this;
    }
    public function deletePolicyAndRule(){
    return $this->delete();
    }


}
