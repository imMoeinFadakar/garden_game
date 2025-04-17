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

    // public function setAvatarImage($value): void
    // {
    //     $this->attributes["image_url"] = $this->uploadImage($value,ucfirst($this->table),$this->image_url ?? false) ?? null;    
    // }

    // public function getAvatarAttribute($value): ?string
    // {
    //     return $this->getImage($value);
    // }

    public function addNewAvatar( $request): Avatar{
        
        $uploadImage = $this->uploadMedia($request,"avatar");
        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage;
        return $this->query()->create( $validtedRequest);
    }

    public function updateAvatar($request): static{
        $this->update($request->validated());
        return $this;
    }

    public function deleteAvatar(): ?bool{
    return $this->delete();
    }

}
