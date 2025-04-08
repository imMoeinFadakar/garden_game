<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Avatar extends Model
{
    use UploadImageTrait;

    protected $fillable = [
        "gender",
        "image_url"
    ] ;



    public function setAvatarAttribute($value): void
    {
        $this->attributes['image_url'] = $this->uploadImage($value , ucfirst($this->table) , $this->image_url ?? false) ?? null;
    }

    public function getAvatarAttribute($value): ?string
    {
        return $this->getImage($value);
    }

    public function addNewAvatar( $request){
        return $this->query()->create( $request->validated());
    }




    public function updateAvatar($request){
        $this->update($request->validated());
        return $this;
    }


    public function deleteAvatar(){
    return $this->delete();
    }

}
