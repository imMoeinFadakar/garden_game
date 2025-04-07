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
        $fullUrl  = $this->uploadImage($request);
        $newRequest = $this->addImagePath($request->validated(),$fullUrl);
        return $this->query()->create($newRequest);
    }

    public function updateFarm($request): static
    {
        $fullUrl  = $this->uploadImage($request);
        $newRequest = $this->addImagePath($request->validated(),$fullUrl);
        $this->update($newRequest);
        return $this;

    }

    public function deleteFarm(): ?bool
    {
        return $this->delete();
    }
}
