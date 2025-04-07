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


        $fullUrl  = $this->uploadImage($request,'Avatar');
        $newRequest = $this->addImagePath($request->validated(),$fullUrl);

    return $this->create( $newRequest);
    }




    public function updateAvatar($request){

    $fullUrl  = $this->uploadImage($request,"Avatar");
    $newRequest = $this->addImagePath($request->validated(),$fullUrl);

    $this->update($newRequest);
    return $this;
    }


    public function deleteAvatar(){
    return $this->delete();
    }

}
