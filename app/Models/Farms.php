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

    public function setFarmImage($value): void
    {
        $this->attributes["image_url"] = $this->uploadImage($value,ucfirst($this->table),$this->image_url ?? false) ?? null;    
    }

    public function getFarmImageAttribute($value): ?string
    {
        return $this->getImage($value);
    }

    public function setFarmFlageImage($value): void
    {
        $this->attributes["flage_image_url"] = $this->uploadImage($value,ucfirst($this->table),$this->flage_image_url ?? false) ?? null;    
    }

    public function getFarmFlageAttribute($value): ?string
    {
        return $this->getImage($value);
    }


    public function addNewFarm($request)
    {
        
        return $this->query()->create($request->validated());
    }

    public function updateFarm($request): static
    {
        $validatedRequest = $request->validated();
        $this->update($validatedRequest);
        return $this;
    }

    public function deleteFarm(): ?bool
    {
        return $this->delete();
    }
}
