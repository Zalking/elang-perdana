<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hutang_retails', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice', 50)->unique();
            $table->date('tanggal_hutang');
            $table->string('nama_retail', 255);
            $table->string('kontak_retail', 20)->nullable();
            $table->foreignId('stok_ban_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_ban')->unsigned(); // Jumlah ban yang dipinjam
            $table->integer('dibayar')->unsigned()->default(0); // Jumlah ban yang sudah dikembalikan
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Basic indexes for performance
            $table->index('tanggal_hutang');
            $table->index('nama_retail');
            $table->index('tanggal_jatuh_tempo');
            $table->index('created_at');

            // Comment untuk dokumentasi
            $table->comment('Tabel untuk menyimpan data hutang retail dalam bentuk jumlah ban');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hutang_retails');
    }
};