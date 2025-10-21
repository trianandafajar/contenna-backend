<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateLogDescription extends Model
{
    use HasFactory;

    public function log()
    {
        return $this->belongsTo(UpdateLog::class);
    }
}
