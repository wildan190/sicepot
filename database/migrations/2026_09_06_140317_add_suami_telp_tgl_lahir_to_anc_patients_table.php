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
        Schema::table('anc_patients', function (Blueprint $table) {
            $table->string('nama_suami')->nullable()->after('nama_lengkap');
            $table->string('no_telepon')->nullable()->after('nik');
            $table->date('tanggal_lahir')->nullable()->after('umur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anc_patients', function (Blueprint $table) {
            $table->dropColumn(['nama_suami', 'no_telepon', 'tanggal_lahir']);
        });
    }
};
