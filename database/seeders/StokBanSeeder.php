<?php

namespace Database\Seeders;

use App\Models\StokBan;
use Illuminate\Database\Seeder;

class StokBanSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        StokBan::query()->delete();

        $this->command->info('Seeder StokBan berhasil dijalankan! Data kosong dan siap untuk diisi manual.');
    }
}