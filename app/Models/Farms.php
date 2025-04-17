<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;

class Farms extends Model
{
    use UploadImageTrait;

    protected $table = "farms";
    protected  $fillable =[
        "name",
        "require_token",
        "require_gem",
        "require_referral",
        "image_url",
        "flage_image_url",
        "description",
        "power",
    ];

  

  


    public function addNewFarm($request)
    {
      
        $uploadImage = $this->uploadMedia($request,"farm");
        $uploadFlageImage = $this->uploadMedia($request,"flage_farm","flage_image_url");

        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage;
        $validtedRequest["flage_image_url"] = $uploadFlageImage;

        return $this->query()->create($validtedRequest);
    }

    public function updateFarm($request): static
    {
        $uploadImage = $this->uploadMedia($request,"farm");
        $uploadFlageImage = $this->uploadMedia($request,"flage_farm","flage_image_url");

        $validtedRequest = $request->validated();
        $validtedRequest["image_url"] = $uploadImage;
        $validtedRequest["flage_image_url"] = $uploadFlageImage;

        $this->update($validtedRequest);
        return $this;
    }

    public function deleteFarm(): ?bool
    {
        return $this->delete();
    }
}
