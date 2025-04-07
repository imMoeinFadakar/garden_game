<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use UploadImageTrait;
    protected $fillable = [
        "farm_id",
        "description",
        "reward",
        "image_url"
    ];


    public function addNewBadge( $request){

        $fullUrl  = $this->uploadImage($request);
        $newRequest = $this->addImagePath($request->validated(),$fullUrl);

        return $this->query()->create( $newRequest);
    }


    public function updateBadge($request): static
    {

        $fullUrl  = $this->uploadImage($request);
        $newRequest = $this->addImagePath($request->validated(),$fullUrl);

        $this->update($newRequest);
        return $this;
    }

    public function deleteBadge(): ?bool
    {
    return $this->delete();
    }

}
