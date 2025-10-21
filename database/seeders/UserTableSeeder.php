<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $issetAdmin = User::where('name', 'administrator')->exists();
        if(!$issetAdmin){
            $admin = User::create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'status' => 1,
                'accept_terms'=>true
            ]);
            $admin->assignRole('super-admin');
        }

        // $issetMentor = User::where('name', 'mentor')->exists();
        // if(!$issetMentor){
        //     $user = User::create([
        //         'name' => 'user',
        //         'email' => 'mentor@gmail.com',
        //         'email_verified_at' => now(),
        //         'password' => Hash::make('password'),
        //         'status' => 1,
        //         'accept_terms'=>true
        //     ]);
        //     $user->assignRole('mentor');
        // }

        // $issetKoordinator = User::where('name', 'koordinator')->exists();
        // if(!$issetKoordinator){
        //     $user = User::create([
        //         'name' => 'user',
        //         'email' => 'koordinator@gmail.com',
        //         'email_verified_at' => now(),
        //         'password' => Hash::make('password'),
        //         'status' => 1,
        //         'accept_terms'=>true
        //     ]);
        //     $user->assignRole('koordinator');
        // }

        // $issetUser = User::where('name', 'anggota')->exists();
        // if(!$issetUser){
        //     $user = User::create([
        //         'name' => 'user',
        //         'email' => 'user@gmail.com',
        //         'email_verified_at' => now(),
        //         'password' => Hash::make('password'),
        //         'status' => 1,
        //         'accept_terms'=>true
        //     ]);
        //     $user->assignRole('anggota');
        // }

    }
}
