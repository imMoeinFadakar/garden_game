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
        
        $uploadImage = $this->uploadMedia($request,"badge");
        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage;
        
        return $this->query()->create($validtedRequest);
    }


    public function updateBadge($request): static
    {
        $uploadImage = $this->uploadMedia($request,"badge");
        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage;
        

        $this->update($validtedRequest);
        return $this;
    }

    public function deleteBadge(): ?bool
    {
    return $this->delete();
    }

}
