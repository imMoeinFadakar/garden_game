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


    public function addNewProduct( $request){
  

        $uploadImage = $this->uploadMedia($request,"product");
        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage;

    return $this->query()->create( $validtedRequest);
    }

    public function updateProduct($request): static
    {   
        $uploadImage = $this->uploadMedia($request,"product");
        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage;

        $this->update($validtedRequest);
        return $this;
    }

    public function deleteProducts(): ?bool
    {
    return $this->delete();
    }

}
