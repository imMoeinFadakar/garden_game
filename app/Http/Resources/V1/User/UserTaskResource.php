<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "task" => $this->task->title,
            "gem_reward" => $this->task->gem_reward,
            "token_reward" => $this->task->token_reward,
            "created_at" => $this->task->created_at,
           

        ];
    }
}
