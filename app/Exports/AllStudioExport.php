<?php

namespace App\Exports;

use App\Models\Business;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllStudioExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Business::with(['circle', 'user'])
            ->where('type', 1)
            ->get()
            ->map(function ($business) {
                return [
                    'name' => $business->name ?? ' ', 
                    'user_name' => optional($business->user)->name ?? ' ',
                    'koordinator_name' => optional($business->businessMemberKoordinator)->user?->name ?? ' ',
                    'no_hp' => optional($business->user)->phone_number ?? ' ',
                    'circle_name' => optional($business->circle)->name ?? ' ',
                    'ecosystem_name' => optional(optional($business->circle)->ecosystem)->name ?? ' ',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Studio',
            'Owner',
            'Koordinator',
            'No Hp',
            'Nama Circle',
            'Nama Ecosystem',
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $worksheet = $event->sheet->getDelegate();
                // Misalnya, Anda memiliki 5 kolom
                $columns = ['A', 'B', 'C', 'D', 'E'];
                foreach ($columns as $column) {
                    $worksheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}