<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Omzet;
use App\Models\Circle;
use App\Models\Business;
use App\Models\Ecosystem;
use App\Models\CircleMember;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'accept_terms',
        'avatar',
        'status',
        'phone_number',
        'address',
        'ktp',
        'upload_ktp',
        'username',
        'gender',
        'dob',
        'pob',
        'npwp',
        'created_by',
        'updated_by',
        'deleted_reason',
    ];

    // Log all changes made to the model
    protected static $logAttributes = ['*'];
    protected static $logFillable = true;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            
            if ($user->isForceDeleting()) {
                // Ubah author_id di blog menjadi NULL jika dihapus permanent
                Blog::where('author_id', $user->id)->update(['author_id' => null]);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']) // Hanya log atribut ini
            ->useLogName('user'); // Gunakan nama log ini
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Blog::class, 'bookmarks')->withTimestamps();
    }
}
