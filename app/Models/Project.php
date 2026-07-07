<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }
}
