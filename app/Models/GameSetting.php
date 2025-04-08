<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;

class GameSetting extends Model
{
    use UploadImageTrait;
    protected $fillable = [
       "title",
       "image_url",
       "discribtion"
    ];


    public function addNewGameSetting( $request): GameSetting{
        $token_image_url =  $this->uploadImage($request,"GameSetting",'image_url');

        $validatedRequest = $request->validated();
         $validatedRequest["image_url"] = $token_image_url;
        
    return $this->query()->create( $validatedRequest);
    }

    public function updateGameSetting($request): static
    {
        $token_image_url =  $this->uploadImage($request,"GameSetting",'token_image_url');
        $gem_image_url =  $this->uploadImage($request,"GameSetting",'gem_image_url');

        
        $validatedRequest = $request->validated();
         $validatedRequest["token_image_url"] = $token_image_url;
         $validatedRequest["gem_image_url"] = $gem_image_url;
        $this->update($validatedRequest);
        return $this;
    }

    public function deleteGameSetting(): bool|null
    {
        return $this->delete();
    }
}
