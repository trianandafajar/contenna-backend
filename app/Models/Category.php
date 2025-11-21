<?php

namespace App\Models;

use Attribute;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'slug', 'status'];

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: function ($value, $attributes) {
                if(!empty($value)) {
                    return Str::slug($value);
                }
                return Str::slug($attributes['name'] . '-' . uniqid());
            }
        );
    }

    // Log all changes made to the model
    protected static $logAttributes = ['*'];
    protected static $logFillable = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']) // Hanya log atribut ini
            ->useLogName('category'); // Gunakan nama log ini
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }
}
