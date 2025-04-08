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



  

    public function addNewAvatar( $request){

        $image_path =  $this->uploadImage($request,"avatar");
        $validatedRequest = $request->validated();
        $validatedRequest["image_url"] = $image_path;
  
        return $this->query()->create( $validatedRequest);
    }

    public function updateAvatar($request){

        $image_path =  $this->uploadImage($request,"avatar");
        $validatedRequest = $request->validated();
        $validatedRequest["image_url"] = $image_path;

        $this->update($validatedRequest);
        return $this;
    }


    public function deleteAvatar(){
    return $this->delete();
    }

}
