<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anc_patients', function (Blueprint $table) {
            // Bulan kunjungan yang diambil dari kolom "BULAN KUNJUNGAN" di Excel
            // Ini terpisah dari kolom `bulan` yang digunakan sebagai metadata laporan umum
            $table->string('bulan_kunjungan')->nullable()->after('bulan');
        });
    }

    public function down(): void
    {
        Schema::table('anc_patients', function (Blueprint $table) {
            $table->dropColumn('bulan_kunjungan');
        });
    }
};
