<?php

namespace App\Trait;

use Illuminate\Support\Facades\Storage;

trait UploadImageTrait
{
    

    public function uploadImage($request)
    {
       $image =  $request->file("image_url"); // find its image 
        $path  = $image->store('images/' , 'public'); // store in db and return path
        return  config('app.url') . Storage::url($path);  // return full path
    }

    public function addImagePath(array $request,$fullUrl)
    {
        $request["image_url"] = $fullUrl;
        return $request;
    }
}
