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
        Schema::create('tb_patients', function (Blueprint $table) {
            $table->id();
            $table->string('report_type')->default('tb_03'); // 'tb_03' (Terkonfirmasi/Pengobatan) or 'tb_06' (Terduga)
            $table->string('fasyankes_name')->nullable();
            $table->string('fasyankes_code')->nullable();

            $table->string('no_urut')->nullable();
            $table->string('no_identitas_sediaan')->nullable();
            $table->string('no_reg_fasyankes')->nullable();
            $table->string('no_reg_kab_kota')->nullable();
            $table->string('no_rekam_medis')->nullable();
            $table->string('nik')->nullable();
            $table->string('no_reg_sitb')->nullable();
            $table->string('no_reg_terduga')->nullable();
            $table->string('no_reg_pasien')->nullable();
            $table->string('no_bpjs')->nullable();

            $table->string('nama_lengkap')->nullable();
            $table->integer('umur')->nullable();
            $table->string('jenis_kelamin', 10)->nullable(); // L / P
            $table->string('pekerjaan')->nullable();

            // Wilayah Pasien
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->text('alamat_lengkap')->nullable();

            // Tanggal & Periode
            $table->string('bulan')->nullable();
            $table->string('tanggal_daftar')->nullable();
            $table->string('tanggal_mulai_pengobatan')->nullable();

            // Medis & Klinis
            $table->string('status_pengobatan')->nullable();
            $table->string('tipe_diagnosis')->nullable();
            $table->string('lokasi_anatomi')->nullable();
            $table->string('riwayat_pengobatan')->nullable();
            $table->string('status_hiv')->nullable();
            $table->string('riwayat_dm')->nullable();
            $table->string('hasil_tcm')->nullable();
            $table->string('hasil_mikroskopis')->nullable();
            $table->string('hasil_diagnosis')->nullable();
            $table->string('hasil_akhir_pengobatan')->nullable(); // Sembuh, Lengkap, Putus berobat, dll.

            $table->json('raw_attributes')->nullable();
            $table->timestamps();

            // Indexes for fast querying & filtering
            $table->index(['kabupaten', 'kelurahan']);
            $table->index('report_type');
            $table->index('nik');
            $table->index('no_reg_sitb');
            $table->index('hasil_akhir_pengobatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_patients');
    }
};
