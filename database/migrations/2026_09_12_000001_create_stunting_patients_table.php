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
            $table->string('jenis_kelamin', 10)->nullable();
            $table->date('tanggal_lahir')->nullable();

            $table->double('bb_lahir')->nullable();
            $table->double('tb_lahir')->nullable();

            $table->string('nama_ortu', 150)->nullable();
            $table->string('puskesmas', 100)->nullable();
            $table->string('desa', 100)->nullable()->index();
            $table->string('posyandu', 100)->nullable();
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('usia_saat_ukur', 100)->nullable();
            $table->date('tanggal_pengukuran')->nullable()->index();

            // Menggunakan double untuk menghindari error desimal/serial date Excel
            $table->double('berat')->nullable();
            $table->double('tinggi')->nullable();
            $table->string('cara_ukur', 30)->nullable();
            $table->double('lila')->nullable();

            // Z-Score categories and values
            $table->string('bbu_kategori', 50)->nullable();
            $table->double('bbu_zscore')->nullable();
            $table->string('tbu_kategori', 50)->nullable();
            $table->double('tbu_zscore')->nullable();
            $table->string('bbtb_kategori', 50)->nullable();
            $table->double('bbtb_zscore')->nullable();

            $table->string('naik_berat_badan', 5)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stunting_patients');
    }
};
