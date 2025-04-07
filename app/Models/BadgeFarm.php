<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeFarm extends Model
{
    protected $fillable = [
        "badge_id",
        "farm_id"
    ];


    /**
     * Get the farm that owns the BadgeFarm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function farm()
    {
        return $this->belongsTo(Farms::class, 'farm_id');
    }

    /**
     * Get the badge that owns the BadgeFarm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }


    public function addNewBadgeFarm( $request){
    return $this->query()->create( $request->validated());
    }

    public function updateBadgeFarm($request): static
    {
    $this->update($request->validated());
    return $this;
    }

    public function deleteBadgeFarm(): ?bool
    {
    return $this->delete();
    }




}
