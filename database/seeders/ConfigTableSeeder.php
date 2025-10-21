<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'app.name' => 'DocuVerse',
            'app.url' => 'https://docuverse.xyz/',
            'mail.from.name' => 'All Fill Dev',
            'mail.from.address' => 'allfilldev@gmail.com',
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => 'smtp.gmail.com',
            'mail.mailers.smtp.port' => '587',
            'mail.mailers.smtp.username' => 'allfilldev@gmail.com',
            'mail.mailers.smtp.password' => 'jyastgrehcyxgrfu',
            'mail.mailers.smtp.encryption' => 'ssl',
            'setting.general.app_logo' => 'logo.png',
            'setting.general.app_favicon' => 'favicon.png',
            'setting.general.app_description' => 'Dokumentasi Digital untuk semua',
            'app.registration_code' => 'developer',
        ];

        foreach ($data as $key => $value) {
            $config = \App\Models\Config::firstOrCreate(['key' => $key]);
            $config->value = $value;
            $config->save();
        }
    }
}
