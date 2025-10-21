<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Config extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['key', 'value'];

    // Log all changes made to the model
    protected static $logAttributes = ['*'];
    protected static $logFillable = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']) // Hanya log atribut ini
            ->useLogName('config'); // Gunakan nama log ini
    }
}
