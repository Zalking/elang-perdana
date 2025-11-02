<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Penjualan::select([
            'kategori',
            'customer', 
            'brand',
            'part',
            'description',
            'ytd',
            'january',
            'february',
            'march',
            'april',
            'may',
            'june',
            'july',
            'august',
            'september',
            'october',
            'mtd',
            'mtd_export',
            'mtd_domestic'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Customer',
            'Brand',
            'Part',
            'Description',
            'YTD',
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'MTD',
            'MTD Export',
            'MTD Domestic'
        ];
    }
}