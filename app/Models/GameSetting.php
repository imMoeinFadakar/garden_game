<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSetting extends Model
{
    protected $fillable = [
        "token_image_url",
        "gem_image_url"
    ];


    public function addNewGameSetting( $request): GameSetting{
    return $this->query()->create( $request->validated());
    }

    public function updateGameSetting($request): static
    {
        $this->update($request->validated());
        return $this;
    }

    public function deleteGameSetting(): bool|null
    {
        return $this->delete();
    }
}
