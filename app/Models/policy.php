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
     *
     */
    public function addNewPolicy($request)
    {
      return  $this->query()->create($request);
    }


    /**
     * Update an exists policy
     * @param mixed $request
     *
     */
    public function updatePolicy($request)
    {
        $this->update($request);
        return $this;
    }


    public function deletePolicy()
    {
        return $this->delete();
    }


}
