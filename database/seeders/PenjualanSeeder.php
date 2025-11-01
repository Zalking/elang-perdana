<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data penjualan yang ada (jika ada)
        Penjualan::truncate();
        
        // Tidak ada data dummy yang dimasukkan
        // Aplikasi akan mulai dengan data kosong
    }
}