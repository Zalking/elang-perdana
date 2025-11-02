<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // Export/Domestic
            $table->string('customer');
            $table->string('brand'); // FORCEUM, ACCELERA, ZEETEX, ARMSTRONG
            $table->string('part');
            $table->text('description');
            $table->integer('ytd')->default(0);
            $table->integer('january')->default(0);
            $table->integer('february')->default(0);
            $table->integer('march')->default(0);
            $table->integer('april')->default(0);
            $table->integer('may')->default(0);
            $table->integer('june')->default(0);
            $table->integer('july')->default(0);
            $table->integer('august')->default(0);
            $table->integer('september')->default(0);
            $table->integer('october')->default(0);
            $table->integer('mtd')->default(0);
            $table->integer('mtd_export')->default(0);
            $table->integer('mtd_domestic')->default(0);
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualans');
    }
};