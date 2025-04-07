<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
     protected $fillable = [
        'text'
     ];


     public function addNewPolicy($request)
     {
        return $this->query()->create($request->Validated());
     }

     public function UpdatePolicies($request): static
     {
        $this->update($request);
        return $this;
     }


     public function deletePolicies(): ?bool
     {
        return $this->delete();
     }
}
