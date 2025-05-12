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
        "min_token_value",
        "max_token_value",
        "farm_image_url",
        "require_token",
        "require_gem",
        "require_referral",
        "prodcut_image_url",
        "flage_image_url",
        "description",
        "power",
        'farm_reward',
        "background_light_color",
        "background_bold_color",
        "header_light_color",
        "header_bold_color"
        
    ];


    public function addNewFarm($request)
    {
      
        $uploadImageFarm = $this->uploadMedia($request,"farm_image_url","farm_image_url");
        $uploadProduct = $this->uploadMedia($request,"prodcut_image_url","prodcut_image_url");


        $validtedRequest = $request->validated();
        $validtedRequest["farm_image_url"] = $uploadImageFarm;

        if(isset($validtedRequest["flage_image_url"]) && $validtedRequest["flage_image_url"] != null){

            $uploadFlageImage = $this->uploadMedia($request,"flage_image_url","flage_image_url");
            $validtedRequest["flage_image_url"] = $uploadFlageImage;
        }

        $validtedRequest["prodcut_image_url"] = $uploadProduct;

        return $this->query()->create($validtedRequest);
    }

    public function updateFarm($request): static
    {
        $uploadImageFarm = $this->uploadMedia($request,"farm_image_url","farm_image_url");
        $uploadProduct = $this->uploadMedia($request,"prodcut_image_url","prodcut_image_url");
        $validtedRequest = $request->validated();

        if(isset($validtedRequest["flage_image_url"]) && $validtedRequest["flage_image_url"] != null){

            $uploadFlageImage = $this->uploadMedia($request,"flage_image_url","flage_image_url");
            $validtedRequest["flage_image_url"] = $uploadFlageImage;
        }

        $validtedRequest["farm_image_url"] = $uploadImageFarm ?? $this->farm_image_url;
        $validtedRequest["prodcut_image_url"] = $uploadProduct ?? $this->prodcut_image_url;
        


        $this->update($validtedRequest);
        return $this;
    }

    public function deleteFarm(): ?bool
    {
        return $this->delete();
    }

    public static function findFarm($farmId)
    {
        return self::query()->find($farmId) ?:null;
    }


}
