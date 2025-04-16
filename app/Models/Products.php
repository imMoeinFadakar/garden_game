<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use UploadImageTrait;

    protected $table ="products";
    protected $fillable = [
        "name",
        "farm_id",
        "min_token_value",
        "max_token_value",
        "user_receive_per_hour",
        "image_url"
    ];


    /**
     * Get the farm that owns the Products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function farm()
    {
        return $this->belongsTo(Farms::class);
    }

    public function setProductImage($value): void
    {
        $this->attributes["image_url"] = $this->uploadImage($value,ucfirst($this->table),$this->image_url ?? false) ?? null;    
    }

    public function getProductAttribute($value): ?string
    {
        return $this->getImage($value);
    }


    public function addNewProduct( $request){
  
    return $this->query()->create( $request->Validated());
    }

    public function updateProduct($request): static
    {
        $this->update($$request->Validated());
        return $this;
    }

    public function deleteProducts(): ?bool
    {
    return $this->delete();
    }

}
