<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            foreach (Config::all() as $setting) {
                \Illuminate\Support\Facades\Config::set($setting->key, $setting->value);
            }
        } catch (\Exception $e) {
             // \Log::info("Database connection not established");
        }
        
        Blade::if('canCustome', function(string $permission, $conditional = false ){
            $user = Auth::user();
            
            return $user->can($permission) || $conditional;
        });

        Blade::if('canAnyCustom', function (array $permissions, bool $conditional = false) {
            $user = Auth::user();
    
            // Periksa apakah user memiliki salah satu izin dalam array
            foreach ($permissions as $permission) {
                if ($user->can($permission)) {
                    return true;
                }
            }
    
            // Kembalikan true jika kondisi tambahan terpenuhi
            return $conditional;
        });
    }
}
