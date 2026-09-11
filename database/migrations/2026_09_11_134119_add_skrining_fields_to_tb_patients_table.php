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
        Schema::table('tb_patients', function (Blueprint $table) {
            $table->string('kategori_usia')->nullable()->after('umur');
            $table->string('no_telepon')->nullable()->after('alamat_lengkap');
            $table->string('nama_pelapor')->nullable()->after('no_telepon');
            $table->string('batuk_2_minggu', 10)->nullable()->after('nama_pelapor'); // Ya / Tidak
            $table->string('bb_turun', 10)->nullable()->after('batuk_2_minggu'); // Ya / Tidak
            $table->string('keringat_malam', 10)->nullable()->after('bb_turun'); // Ya / Tidak
            $table->string('kontak_tb', 10)->nullable()->after('keringat_malam'); // Ya / Tidak
            $table->string('sudah_pengobatan', 20)->nullable()->after('kontak_tb'); // Sudah / Belum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_patients', function (Blueprint $table) {
            $table->dropColumn([
                'kategori_usia',
                'no_telepon',
                'nama_pelapor',
                'batuk_2_minggu',
                'bb_turun',
                'keringat_malam',
                'kontak_tb',
                'sudah_pengobatan',
            ]);
        });
    }
};
