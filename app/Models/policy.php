<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $table = "policies";
    protected $fillable = [
        "text"
    ];


    /**
     * Add new policy in database
     * @param mixed $request
     * @return Policy
     */
    public function addNewPolicy($request): Policy
    {
      return  $this->query()->create($request);
    }


    /**
     * Update an exists policy
     * @param mixed $request
     * @return Policy
     */
    public function updatePolicy($request): static
    {
        $this->update($request);
        return $this;
    }


    public function deletePolicy(): ?bool
    {
        return $this->delete();
    }


}