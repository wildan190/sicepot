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
        Schema::create('tb_contact_investigations', function (Blueprint $table) {
            $table->id();

            // Puskesmas / Fasyankes
            $table->string('fasyankes_name')->nullable();
            $table->string('fasyankes_code')->nullable();
            $table->string('periode')->nullable();

            // Petugas & Pengirim
            $table->string('petugas_investigasi')->nullable(); // Nama Kader / Petugas Kesehatan
            $table->string('fasyankes_kader')->nullable();
            $table->string('dirujuk_oleh')->nullable();

            // Kasus Indeks (Index Patient)
            $table->string('kasus_indeks_sitb')->nullable()->index(); // Relasi ke tb_patients.no_reg_sitb
            $table->string('kasus_indeks_nama')->nullable()->index();
            $table->integer('kasus_indeks_umur')->nullable();
            $table->string('kasus_indeks_tipe_diagnosis')->nullable();
            $table->string('kasus_indeks_tgl_diagnosis')->nullable();
            $table->string('kasus_indeks_jenis')->nullable(); // TBC SO, TBC RO, dll.

            // Identitas Kontak (Contact Investigated)
            $table->string('nama_kontak')->nullable()->index();
            $table->string('nik_kontak')->nullable()->index();
            $table->integer('umur_kontak')->nullable();
            $table->string('jenis_kelamin_kontak', 10)->nullable(); // L / P
            $table->text('alamat_kontak')->nullable();

            // Wilayah Kontak / Indeks
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable()->index();

            // Detail Investigasi
            $table->string('metode_ik')->nullable(); // Investigasi Kontak / Invitasi Kontak
            $table->string('jenis_kontak')->nullable(); // Kontak Serumah / Kontak Erat
            $table->string('tanggal_investigasi')->nullable();
            $table->string('diperiksa_toraks')->nullable();

            // Gejala & Faktor Risiko
            $table->string('gejala_batuk')->nullable();
            $table->string('gejala_lain')->nullable();
            $table->string('faktor_risiko')->nullable();

            // Hasil Pemeriksaan & TPT
            $table->string('status_rujukan_terduga')->nullable();
            $table->string('hasil_evaluasi')->nullable(); // Sakit TBC, Terduga TBC, Tidak TBC
            $table->string('status_tpt')->nullable();

            $table->json('raw_attributes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['kabupaten', 'kelurahan']);
            $table->index('jenis_kontak');
            $table->index('hasil_evaluasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_contact_investigations');
    }
};
