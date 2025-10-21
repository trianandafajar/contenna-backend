<?php

namespace App\Exports;
use App\Models\Business;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudioExport implements FromCollection
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if ($this->request->has('start_date', 'end_date')) {
            $start_date = $this->request->input('start_date');
            $end_date = $this->request->input('end_date');
            $startDate = Carbon::createFromDate(intval(substr($start_date, 0, 4)), intval(substr($start_date, 5, 2)), intval(substr($start_date, 8, 2)))->startOfDay();
            $endDate = Carbon::createFromDate(intval(substr($end_date, 0, 4)), intval(substr($end_date, 5, 2)), intval(substr($end_date, 8, 2)))->startOfDay();
        } else {
            $startDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfDay();
            $endDate = $startDate->copy()->endOfMonth();
        }

        $businesses = Business::with(['circle', 'omzet', 'omzet.omzetCategory', 'user'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereHas('omzet', function ($subquery) use ($startDate, $endDate) {
                    $subquery->whereHas('omzetCategory', function ($innerSubquery) use ($startDate, $endDate) {
                        $innerSubquery->whereBetween('date', [$startDate, $endDate]);
                    });
                });
            })
            ->where('type', 1)
            ->get()
            ->map(function ($business) use ($startDate, $endDate) {
                $business->omzetCategory = $business->omzetCategory->filter(function ($omzetCategory) use ($startDate, $endDate) {
                    $date = Carbon::parse($omzetCategory->date);
                    return $date->greaterThanOrEqualTo($startDate) && $date->lessThanOrEqualTo($endDate);
                })->groupBy('category_id')->map(function ($items, $categoryId) {
                    return $items->reduce(function ($carry, $item) {
                        return $carry + $item->income;
                    }, 0);
                })->all();
                return $business;
            });

        $totalIncome = $businesses->map(function ($business) {
            $business->total_income = collect($business->omzetCategory)->sum();
        });

        $sortBy = $this->request->input('sort_by');

        if ($this->request->filled('sort_by')) {
            $businesses = $businesses->sortByDesc(function ($business) use ($sortBy) {
                return $business->omzetCategory[$sortBy];
            });
        } else {
            $businesses = $businesses->sortByDesc('total_income');
        }
        $platform = Category::get();

        $startCarbon = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);
        $startMonth = $startCarbon->format('F');
        $endMonth = $endCarbon->format('F');

        $data = [];
        $headerRow = ['Rank', 'Circle', 'Studio Name'];
        foreach ($platform as $item) {
            $headerRow[] = $item->name;
        }
        $headerRow[] = 'Total Income';
        $dateRange = "Studio Report Date : {$startCarbon->day}-{$startMonth}-{$startCarbon->year} to {$endCarbon->day}-{$endMonth}-{$endCarbon->year}";
        $firstRow = array_merge([$dateRange], array_fill(0, count($headerRow) - 1, ''));
        $data[] = $firstRow;
        $data[] = $headerRow;
        $ranking = 1;
        foreach ($businesses as $key => $business) {
            $rowDataNum = [$ranking++];
            $rowData = [$business->circle?->name, $business->name];
            if (isset($business->omzet[0])) {
                foreach ($business->omzetCategory as $omzetCategory) {
                    $rowData[] = $omzetCategory ? $omzetCategory : "0";
                }
                $rowData[] = $business->total_income;
            } else {
                foreach ($platform as $item) {
                    $rowData[] = "0";
                }
                $rowData[] = "0";
            }
            $data[] = array_merge($rowDataNum, $rowData);
        }

        return collect($data);
    }
}
