<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        $user = new User([
            'ktp' => $row['no_ktp'],
            'phone_number' => $row['phone'], 
            'name' => $row['name'], 
            'email' => $row['email'], 
            'address' => $row['address'], 
            'password' => Hash::make('password'), 
            'created_by' => 560, 
        ]);
    
        $user->save();
        $user->assignRole('anggota');
    
        return $user;
    }
}
