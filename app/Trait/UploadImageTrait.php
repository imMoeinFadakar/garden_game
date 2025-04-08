<?php

namespace App\Trait;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait UploadImageTrait
{
    

    public function uploadImage($request,$dirName)
    {
       $image =  $request->file("image_url"); // find its image 
        $image_name = rand(10,1000) .Carbon::now()->microsecond.'.'.$image->extension();
        $path  = $image->storeAs('images/'.$dirName , $image_name,); // store in storage and return path
        return   Storage::url($path);  // return full path
    }

    public function addImagePath(array $request,$fullUrl)
    {
        $request["image_url"] = $fullUrl;
        return $request;
    }
}
