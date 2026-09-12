<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stunting_patients', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->nullable()->index();
            $table->string('nama', 150);
            $table->string('jenis_kelamin', 10)->nullable(); // L / P
            $table->date('tanggal_lahir')->nullable();

            // PERBAIKAN 1: Ubah (5,2) ke (8,2) agar muat angka gram (misal: 3000)
            $table->decimal('bb_lahir', 8, 2)->nullable();   // gram / kg
            $table->decimal('tb_lahir', 5, 1)->nullable();   // cm

            $table->string('nama_ortu', 150)->nullable();
            $table->string('puskesmas', 100)->nullable();
            $table->string('desa', 100)->nullable()->index();
            $table->string('posyandu', 100)->nullable();
            $table->string('rt', 10)->nullable();             // Diubah ke 10 untuk antisipasi format RT panjang
            $table->string('rw', 10)->nullable();             // Diubah ke 10 untuk antisipasi format RW panjang
            $table->string('alamat', 255)->nullable();
            $table->string('usia_saat_ukur', 100)->nullable();
            $table->date('tanggal_pengukuran')->nullable()->index();
            $table->decimal('berat', 5, 2)->nullable();      // kg
            $table->decimal('tinggi', 5, 1)->nullable();     // cm
            $table->string('cara_ukur', 30)->nullable();
            $table->decimal('lila', 5, 1)->nullable();

            // Z-Score categories and values
            $table->string('bbu_kategori', 50)->nullable();  // BB/U

            // PERBAIKAN 2: Menggunakan double/float agar desimal panjang dari kalkulasi z-score tidak error
            $table->double('bbu_zscore')->nullable();
            $table->string('tbu_kategori', 50)->nullable();  // TB/U
            $table->double('tbu_zscore')->nullable();
            $table->string('bbtb_kategori', 50)->nullable(); // BB/TB
            $table->double('bbtb_zscore')->nullable();

            $table->string('naik_berat_badan', 5)->nullable(); // Y/N/T
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stunting_patients');
    }
};
