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
        Schema::create('propertis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable(false);
            $table->string('alamat', 100)->nullable(false);
            $table->enum('type', ['Rumah', 'Ruko', 'Tanah', 'Apartemen'])->nullable(false);
            $table->boolean('baru')->nullable(false);

            $table->string('harga')->nullable(false);
            $table->string('thumbnail')->nullable()->default('https://images.unsplash.com/photo-1494526585095-c41746248156?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            $table->string('sertifikat', 100);
            $table->string('jumlah_kamar_tidur', 100);
            $table->string('jumlah_kamar_mandi', 100);
            $table->string('kolam_renang', 100);
            $table->string('gudang', 100);
            $table->string('garasi', 100);
            $table->string('jumlah_dapur', 100);
            $table->string('tingkat', 100);
            $table->string('listrik', 100);
            $table->string('luas_tanah', 100);
            $table->string('luas_bangunan', 100);
            $table->string('deskripsi');
            $table->text('lokasi')->nullable(false);
            $table->text('vr')->nullable(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propertis');
    }
};
