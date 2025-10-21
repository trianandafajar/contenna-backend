<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select(
            'id',
            'name',
            'ktp', 
            'phone_number',
            'email',
            'address',
            )->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'Name',
            'No KTP',
            'Phone',
            'Email',
            'Address',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],

            // Styling a specific cell by coordinate.
            'B2' => ['font' => ['italic' => true]],

        ];
    }
}
