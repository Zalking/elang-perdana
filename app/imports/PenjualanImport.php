<?php

namespace App\Imports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;

class PenjualanImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 3; // Skip header rows
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row[1]) || $row[1] === 'Customer') {
            return null;
        }

        return new Penjualan([
            'kategori' => $row[0] ?? '',
            'customer' => $row[1] ?? '',
            'brand' => $row[2] ?? '',
            'part' => $row[3] ?? '',
            'description' => $row[4] ?? '',
            'ytd' => $row[5] ?? 0,
            'january' => $row[6] ?? 0,
            'february' => $row[7] ?? 0,
            'march' => $row[8] ?? 0,
            'april' => $row[9] ?? 0,
            'may' => $row[10] ?? 0,
            'june' => $row[11] ?? 0,
            'july' => $row[12] ?? 0,
            'august' => $row[13] ?? 0,
            'september' => $row[14] ?? 0,
            'october' => $row[15] ?? 0,
            'mtd' => $row[16] ?? 0,
            'mtd_export' => $row[17] ?? 0,
            'mtd_domestic' => $row[18] ?? 0,
            'user_id' => Auth::id(),
        ]);
    }
}