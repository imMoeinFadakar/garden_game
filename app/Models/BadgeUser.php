<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeUser extends Model
{
    protected  $fillable = [
        "user_id",
        "badge_id"
    ];


    /**
     * Get the badge that owns the BadgeFarm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }

     /**
     * Get the user that owns the UserAvatar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }

    public function addNewBadgeUser($request)
    {
       return  $this->query()->create($request->validated());
    }

    public function updateBadgeUser($request): static
    {
        $this->update($request->validated());
        return $this;
    }

    public function deleteBadgeUser(): ?bool
    {
        return $this->delete();
    }

}
