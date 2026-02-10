<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('varietas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama_varietas');
            $table->string('komoditas');
            $table->string('kategori');
            $table->integer('stok');
            $table->string('url_img');
            $table->integer('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('varietas');
    }
};
