<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseLevel extends Model
{
    //
    protected $fillable = [
        "level_number",
        "Overcapacity",
        "cost_for_buy"
    ];

    public function addNewWarehouseLevel($request)
    {
        return $this->query()->create($request->validated());
    }


    public function updateWarehouseLevel($request): static
    {
    $this->update($request->validated());
    return $this;
    }


    public function deleteWarehouseLevel(): ?bool
    {
    return $this->delete();
    }
}
