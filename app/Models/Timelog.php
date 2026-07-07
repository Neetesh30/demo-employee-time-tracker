<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timelog extends Model
{
    
    protected $fillable = [
        'user_id',
        'work_date',
        'project_id',
        'task_description',
        'hours',
        'minutes',
        'total_minutes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
