<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use UploadImageTrait;
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
        $image_path =  $this->uploadImage($request,"product",'image_url');
        
       $validatedRequest = $request->validated();
        $validatedRequest["image_url"] = $image_path;
    return $this->query()->create( $validatedRequest);
    }

    public function updateProduct($request): static
    {
        $image_path =  $this->uploadImage($request,"product",'image_url');
        
        $validatedRequest = $request->validated();
         $validatedRequest["image_url"] = $image_path;
    $this->update($validatedRequest);
    return $this;
    }

    public function deleteProducts(): ?bool
    {
    return $this->delete();
    }

}
