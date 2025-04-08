<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;

class Farms extends Model
{
    use UploadImageTrait;

    protected  $fillable =[
        "name",
        "require_token",
        "require_gem",
        "require_referral",
        "image_url",
        "description",
        "power",
    ];


    public function addNewFarm($request)
    {
        $image_path =  $this->uploadImage($request,"farm",'image_url');
        $flage_image_path =  $this->uploadImage($request,"farm_flag",'flage_image_url');

        $validatedRequest = $request->validated();

        $validatedRequest["image_url"] = $image_path;
        $validatedRequest["flage_image_url"] = $flage_image_path;


        return $this->query()->create($validatedRequest);
    }


    

    public function updateFarm($request): static
    {
        $image_path =  $this->uploadImage($request,"farm",'image_url');
        $flage_image_path =  $this->uploadImage($request,"farm_flag",'flage_image_url');

        $validatedRequest = $request->validated();

        $validatedRequest["image_url"] = $image_path;
        $validatedRequest["flage_image_url"] = $flage_image_path;

        $this->update($validatedRequest);
        return $this;

    }

    public function deleteFarm(): ?bool
    {
        return $this->delete();
    }
}
