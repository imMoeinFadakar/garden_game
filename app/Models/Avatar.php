<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Avatar extends Model
{
    use UploadImageTrait;

    protected $table = "avatars";


    protected $fillable = [
        "gender",
        "image_url"
    ] ;

  

    public function addNewAvatar($request): Avatar{
        
        $uploadImage = $this->uploadMedia($request,"avatar");
        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage;

        return $this->query()->create( $validtedRequest);
    }

    public function updateAvatar($request): static{
        
        $uploadImage = $this->uploadMedia($request,"avatar");
        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage ?: $this->image_url;

        $this->update($validtedRequest);
        return $this;
    }

    public function deleteAvatar(): ?bool{
    return $this->delete();
    }

}
