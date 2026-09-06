<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anc_patients', function (Blueprint $table) {
            $table->id();

            // Source info
            $table->string('fasyankes_name')->nullable();
            $table->string('fasyankes_code')->nullable();
            $table->string('bulan')->nullable();
            $table->string('tahun')->nullable();

            // Identitas pasien
            $table->string('nik')->nullable()->index();
            $table->string('no_reg_fasyankes')->nullable();
            $table->string('no_rekam_medis')->nullable();
            $table->string('no_bpjs')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->unsignedSmallInteger('umur')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('pendidikan')->nullable();

            // Wilayah
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable()->index();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable()->index();
            $table->text('alamat_lengkap')->nullable();

            // Kehamilan
            $table->string('golongan_darah')->nullable();
            $table->unsignedSmallInteger('gravida')->nullable();   // jumlah kehamilan
            $table->unsignedSmallInteger('para')->nullable();      // jumlah persalinan
            $table->unsignedSmallInteger('abortus')->nullable();   // jumlah abortus
            $table->string('usia_kehamilan')->nullable();          // trimester / minggu
            $table->date('hpht')->nullable();                      // hari pertama haid terakhir
            $table->date('hpl')->nullable();                       // hari perkiraan lahir
            $table->date('tanggal_kunjungan')->nullable();
            $table->string('kunjungan_ke')->nullable();            // K1/K2/K3/K4/K5/K6
            $table->string('jenis_kunjungan')->nullable();         // baru / lama / bumil risiko

            // Pemeriksaan
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('lila', 5, 2)->nullable();             // lingkar lengan atas
            $table->decimal('tekanan_darah_sistolik', 5, 1)->nullable();
            $table->decimal('tekanan_darah_diastolik', 5, 1)->nullable();
            $table->decimal('tinggi_fundus_uteri', 5, 2)->nullable();
            $table->string('presentasi_janin')->nullable();        // kepala / sungsang
            $table->string('denyut_jantung_janin')->nullable();
            $table->string('status_imunisasi_tt')->nullable();     // TT1-TT5 / TT Lengkap

            // Lab & Intervensi
            $table->string('hb')->nullable();                      // kadar hemoglobin
            $table->string('status_anemia')->nullable();
            $table->string('gds')->nullable();                     // gula darah sewaktu
            $table->string('protein_urine')->nullable();
            $table->string('hbsag')->nullable();
            $table->string('hiv_status')->nullable();
            $table->string('sifilis_status')->nullable();
            $table->boolean('mendapat_fe')->default(false);        // tablet tambah darah
            $table->boolean('mendapat_vit_a')->default(false);
            $table->string('p4k')->nullable();                     // program perencanaan persalinan

            // Risiko & Rujukan
            $table->string('faktor_risiko')->nullable();
            $table->string('risiko_tinggi')->nullable();
            $table->string('status_risti')->nullable();            // normal / risti
            $table->string('dirujuk_ke')->nullable();
            $table->string('alasan_rujukan')->nullable();

            // Outcome
            $table->string('status_kehamilan')->nullable();        // aktif / bersalin / meninggal / pindah
            $table->string('tempat_bersalin')->nullable();
            $table->string('penolong_persalinan')->nullable();
            $table->date('tanggal_bersalin')->nullable();
            $table->string('komplikasi_persalinan')->nullable();
            $table->string('kondisi_bayi')->nullable();            // hidup / lahir mati
            $table->decimal('berat_lahir_bayi', 5, 2)->nullable();
            $table->string('catatan')->nullable();

            // Raw / extra
            $table->json('raw_attributes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anc_patients');
    }
};
