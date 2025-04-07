<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailbox extends Model
{
    protected $fillable = [
        "title",
        "body"
    ];

    public function addNewMailbox($request)
    {
        return $this->query()->create($request);
    }

    public function updateMailbox($request): static
    {
        $this->update($request);
        return $this;
    }

    public function deleteMailbox(): ?bool
    {
        return $this->delete();
    }

}
