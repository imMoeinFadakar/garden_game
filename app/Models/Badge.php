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

    public function setBadgeImage($value): void
    {
        $this->attributes["image_url"] = $this->uploadImage($value,ucfirst($this->table),$this->image_url ?? false) ?? null;    
    }

    public function getBadgeAttribute($value): ?string
    {
        return $this->getImage($value);
    }

    public function addNewBadge( $request){
        return $this->query()->create($$request->validated());
    }


    public function updateBadge($request): static
    {
        $image_path =  $this->uploadImage($request,"badge",'image_url'); /// add 

        $validatedRequest = $request->validated();
        $validatedRequest["image_url"] = $image_path;
        

        $this->update($validatedRequest);
        return $this;
    }

    public function deleteBadge(): ?bool
    {
    return $this->delete();
    }

}
