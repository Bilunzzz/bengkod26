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
        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->foreign('id_jadwal')->references('id')->on('jadwal_periksa')->cascadeOnDelete();
        });

        Schema::table('detail_periksa', function (Blueprint $table) {
            $table->foreign('id_obat')->references('id')->on('obat')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_periksa', function (Blueprint $table) {
            $table->dropForeign(['id_obat']);
        });

        Schema::table('daftar_poli', function (Blueprint $table) {
            $table->dropForeign(['id_jadwal']);
        });
    }
};
