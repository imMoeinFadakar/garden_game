<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    //
    protected $fillable = [
        "user_id",
        "task_id"
    ];

    /**
     * Get the user that owns the UserTask
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get the task that owns the UserTask
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    public function addNewUserTask($request)
    {
        return $this->create($request->validated());
    }

}
