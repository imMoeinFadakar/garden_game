<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAvatar extends Model
{
    protected $fillable = [
        "user_id",
        "avatar_id"
    ];

    /**
     * Get the user that owns the UserAvatar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }

    /**
     * Get the avatar that owns the UserAvatar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function avatar()
    {
        return $this->belongsTo(Avatar::class, 'avatar_id', 'id');
    }

    public function addNewUserAvatar($request)
    {
        return   $this->query()->create($request);
    }

    public function updateUserAvatar($request): static
    {
        $this->update($request->validated());
        return $this;
    }

    public function deleteUserAvatar(): ?bool
    {
        return $this->delete();
    }
}
