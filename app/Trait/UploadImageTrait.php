<?php

namespace App\Trait;

use Carbon\Carbon;
use Faker\Core\Uuid;
use Illuminate\Support\Facades\Storage;

trait UploadImageTrait
{
    const CDN_URL = 'https://appzhixgame.online/upload/';

    public function uploadMedia($request, $dirName, $index = 'image_url', $oldPath = null)
    {
        if($request->hasFile($index) == null)
        return null;



        $file = $request->file($index);
        $fileName = $this->generateFileName($file);

        $fullPath = public_path('upload/' . $dirName);




        $this->deleteOldFileIfExists($oldPath);




        $file->move($fullPath, $fileName);

        return self::CDN_URL . "$dirName/$fileName";
    }

    private function generateFileName($file)
    {
        return uniqid() . rand(10, 1000) . now()->microsecond . '.' . $file->extension();
    }

    private function deleteOldFileIfExists($oldUrl)
    {
        if (!$oldUrl) return;

        $relative = str_replace(self::CDN_URL, '', $oldUrl);
        $filePath = public_path("upload/$relative");

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }





}
