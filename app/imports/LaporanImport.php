<?php

namespace App\Imports;

use App\Models\Laporan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty($row['tanggal']) || empty($row['nama_perusahaan'])) {
                continue;
            }

            Laporan::create([
                'tanggal' => $this->parseDate($row['tanggal']),
                'nama_perusahaan' => $row['nama_perusahaan'] ?? '',
                'posisi' => $row['posisi'] ?? '',
                'status' => $row['status'] ?? 'Pending',
                'keterangan' => $row['keterangan'] ?? '',
                'user_id' => Auth::id(),
            ]);
        }
    }

    private function parseDate($date)
    {
        try {
            if (is_numeric($date)) {
                return Carbon::createFromDate(1900, 1, 1)->addDays($date - 2);
            }
            
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return now();
        }
    }
}