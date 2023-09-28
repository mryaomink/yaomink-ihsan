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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('guru_id'); // Kolom untuk kunci luar ke tabel guru
            $table->unsignedBigInteger('sekolah_id'); // Kolom untuk kunci luar ke tabel sekolah
            $table->string('status'); // Kolom status absensi (terlambat atau tidak)
            $table->time('jam_masuk')->nullable(); // Kolom untuk jam masuk
            $table->time('jam_pulang')->nullable(); // Kolom untuk jam pulang
            $table->string('lokasi_absensi'); // Kolom untuk lokasi absensi
            $table->date('tanggal');
            $table->timestamps();

             $table->foreign('guru_id')->references('id')->on('guru')->onDelete('cascade');
            $table->foreign('sekolah_id')->references('id')->on('sekolah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
