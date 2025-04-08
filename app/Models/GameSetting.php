<?php

namespace App\Models;

use App\Trait\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;

class GameSetting extends Model
{
    use UploadImageTrait;
    protected $fillable = [
        "token_image_url",
        "gem_image_url"
    ];


    public function addNewGameSetting( $request): GameSetting{
        $fullUrl  = $this->uploadImage($request);
        $newRequest = $this->addImagePath($request->validated(),$fullUrl);
    return $this->query()->create( $newRequest);
    }

    public function updateGameSetting($request): static
    {
        $fullUrl  = $this->uploadImage($request);
        $newRequest = $this->addImagePath($request->validated(),$fullUrl);
        $this->update($newRequest);
        return $this;
    }

    public function deleteGameSetting(): bool|null
    {
        return $this->delete();
    }
}
