<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use UploadImageTrait;
    protected $fillable = [
        "farm_id",
        "description",
        "reward",
        "image_url"
    ];


    public function addNewBadge( $request){

        

       $image_path =  $this->uploadImage($request);
        
        $validatedRequest = $request->validated();
        $validatedRequest["image_url"] = $image_path;
  

       
      
        return $this->query()->create($validatedRequest);
    }


    public function updateBadge($request): static
    {

        $fullUrl  = $this->uploadImage($request);
        

        // $this->update($newRequest);
        return $this;
    }

    public function deleteBadge(): ?bool
    {
    return $this->delete();
    }

}
