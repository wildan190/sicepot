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
        Schema::table('stunting_patients', function (Blueprint $table) {
            $table->string('prov', 100)->nullable()->after('nama_ortu');
            $table->string('kab_kota', 100)->nullable()->after('prov');
            $table->string('kec', 100)->nullable()->after('kab_kota');
            $table->integer('jml_vit_a')->nullable()->after('naik_berat_badan');
            $table->string('kpsp', 50)->nullable()->after('jml_vit_a');
            $table->string('kia', 50)->nullable()->after('kpsp');
            $table->string('kelas_ibu', 50)->nullable()->after('kia');
            $table->string('mbg', 50)->nullable()->after('kelas_ibu');
            $table->string('test_mantoux', 20)->nullable()->after('mbg');
            $table->string('test_hemoglobin', 20)->nullable()->after('test_mantoux');
            $table->string('konsul_spa', 20)->nullable()->after('test_hemoglobin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stunting_patients', function (Blueprint $table) {
            $table->dropColumn([
                'prov', 'kab_kota', 'kec', 'jml_vit_a', 'kpsp', 'kia',
                'kelas_ibu', 'mbg', 'test_mantoux', 'test_hemoglobin', 'konsul_spa'
            ]);
        });
    }
};
