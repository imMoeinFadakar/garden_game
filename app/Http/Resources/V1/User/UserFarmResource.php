<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFarmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "farm_id" => $this->farm_id,
            "farm" => [
                "name" => $this->farm->name,
                "image_url" => $this->farm->image_url,
                "flage_image_url" => $this->farm->flage_image_url,
                "description" => $this->farm->description,
                "power" => $this->farm->power,  
            ] 
        ];
    }
}
