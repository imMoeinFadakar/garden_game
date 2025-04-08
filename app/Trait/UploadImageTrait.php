<?php

namespace App\Trait;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait UploadImageTrait
{
    
    /**
     * add image in storage dir 
     * @param mixed $request // the request has image file
     * @param mixed $dirName // find or create directory for new image
     * @param mixed $image_key // key name of image attr in request array 
     * @return string // image full path
     */
    public function uploadImage($request,$dirName,$image_key)
    {
       $image =  $request->file($image_key); // find its image 
        $image_name = $image_name = uniqid().Carbon::now()->microsecond . rand(10,1000) . '.' . $image->extension();
        $path  = $image->storeAs('images/'.$dirName , $image_name,); // store in storage and return path
        return   Storage::url($path);  // return full path
    }


 
}
