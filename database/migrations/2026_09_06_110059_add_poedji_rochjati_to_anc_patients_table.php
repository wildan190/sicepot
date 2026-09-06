<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anc_patients', function (Blueprint $table) {
            $table->integer('skor_poedji_rochjati')->default(2)->after('status_risti')->nullable();
            $table->string('kategori_poedji_rochjati', 50)->default('KRR')->after('skor_poedji_rochjati')->nullable(); // KRR, KRT, KRST
            $table->string('rekomendasi_faskes', 100)->nullable()->after('kategori_poedji_rochjati'); // BPM/Puskesmas, RS Rujukan
            $table->string('calon_pendonor', 255)->nullable()->after('rekomendasi_faskes');
        });
    }

    public function down(): void
    {
        Schema::table('anc_patients', function (Blueprint $table) {
            $table->dropColumn(['skor_poedji_rochjati', 'kategori_poedji_rochjati', 'rekomendasi_faskes', 'calon_pendonor']);
        });
    }
};
