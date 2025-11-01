<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_bans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ban')->unique();
            $table->string('nama_ban');
            $table->text('deskripsi')->nullable();
            $table->string('brand');
            $table->string('ukuran');
            $table->string('type');
            $table->integer('stok')->default(0);
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('total_nilai_stok', 15, 2)->default(0);
            $table->enum('status', ['Tersedia', 'Hampir Habis', 'Habis'])->default('Tersedia');
            $table->integer('minimum_stok')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_bans');
    }
};