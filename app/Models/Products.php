<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        "name",
        "farm_id",
        "min_token_value",
        "max_token_value",
        "user_receive_per_hour"
    ];


    /**
     * Get the farm that owns the Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function farm()
    {
        return $this->belongsTo(Farms::class, 'foreign_key', 'other_key');
    }

    public function addNewProduct( $request){
    return $this->query()->create( $request->validated());
    }

    public function updateProduct($request): static
    {
    $this->update($request->validated());
    return $this;
    }

    public function deleteProducts(): ?bool
    {
    return $this->delete();
    }

}
