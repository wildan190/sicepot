<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stunting_patients', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->nullable()->index();
            $table->string('nama', 150);
            $table->string('jenis_kelamin', 10)->nullable(); // L / P
            $table->date('tanggal_lahir')->nullable();
            $table->decimal('bb_lahir', 5, 2)->nullable();   // kg
            $table->decimal('tb_lahir', 5, 1)->nullable();   // cm
            $table->string('nama_ortu', 150)->nullable();
            $table->string('puskesmas', 100)->nullable();
            $table->string('desa', 100)->nullable()->index();
            $table->string('posyandu', 100)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('usia_saat_ukur', 100)->nullable();
            $table->date('tanggal_pengukuran')->nullable()->index();
            $table->decimal('berat', 5, 2)->nullable();      // kg
            $table->decimal('tinggi', 5, 1)->nullable();     // cm
            $table->string('cara_ukur', 30)->nullable();
            $table->decimal('lila', 5, 1)->nullable();
            // Z-Score categories and values
            $table->string('bbu_kategori', 50)->nullable();  // BB/U
            $table->decimal('bbu_zscore', 6, 2)->nullable();
            $table->string('tbu_kategori', 50)->nullable();  // TB/U
            $table->decimal('tbu_zscore', 6, 2)->nullable();
            $table->string('bbtb_kategori', 50)->nullable(); // BB/TB
            $table->decimal('bbtb_zscore', 6, 2)->nullable();
            $table->string('naik_berat_badan', 5)->nullable(); // Y/N/T
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stunting_patients');
    }
};
