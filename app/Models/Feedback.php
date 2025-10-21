<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Feedback extends Model
{
    use HasFactory, LogsActivity;

    // Log all changes made to the model
    protected static $logAttributes = ['*'];
    protected static $logFillable = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']) // Hanya log atribut ini
            ->useLogName('feedback'); // Gunakan nama log ini
    }

    protected $table = 'feedbacks';

    protected $fillable = [
        'name',
        'email',
        'type', // Mengubah atribut 'date' menjadi 'type' di dalam fillable
        'message',
        'file'
    ];

    // Menghapus definisi 'date' dari $dates
}
