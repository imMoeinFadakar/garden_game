<?php

namespace App\Trait;

use Carbon\Carbon;
use Faker\Core\Uuid;
use Illuminate\Support\Facades\Storage;

trait UploadImageTrait
{
    const CDN_URL = 'https://bucketgarden.storage.c2.liara.space/';
    
    public function uploadMedia($request,$dirName)
    {
        $image =  $request->file("image_url"); // find its image 
        $image_name = uniqid() .  rand(10,1000) .Carbon::now()->microsecond.'.'.$image->extension();
        $path  = $image->storeAs('images/'.$dirName , $image_name,); // store in storage and return path
        return self::CDN_URL . $path;
    }





     public function uploadImage($file , $path , $old_image = false): string
     {
         $path = 'image/'.$path;
         $this->removeImage($path , $old_image);
         $fileExtension = $file->extension();
         $image_name = uniqid().Carbon::now()->microsecond . rand(10,1000) . '.' . $fileExtension;
         $file->storeAs($path, $image_name);
         return $path.'/'.$image_name;
     }
 
     public function getImage($name): ?string
     {
 
         return ($name) ? $this->getUrl($name) : NUll;
     }
 
     public function removeImage($path , $old_image): void
     {
         if (!empty($old_image)){
             if (storage::exists($this->deleteUrl($path , $old_image))){
                 Storage::delete($this->deleteUrl($path , $old_image));
             }
         }
     }
 
 
     public function getUrl($name)
     {
         if (in_array(env('FILESYSTEM_DISK') , ['local','public'])){
             return url('storage/'. $name);
         }
         else{
             return self::CDN_URL.$name;
         }
     }
 
     public function deleteUrl($path , $old_image): string
     {
         if (in_array(env('FILESYSTEM_DISK') , ['local','public'])){
             return  $path .'/' . basename($old_image);
         }
         else{
             return str_replace(self::CDN_URL,'',$old_image);
         }
     }

 
}
