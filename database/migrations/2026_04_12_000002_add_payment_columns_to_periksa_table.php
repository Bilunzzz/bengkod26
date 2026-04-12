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
        Schema::table('periksa', function (Blueprint $table) {
            $table->string('status_pembayaran', 30)->default('belum_bayar')->after('biaya_periksa');
            $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
            $table->dateTime('tgl_bayar')->nullable()->after('bukti_pembayaran');
            $table->foreignId('diverifikasi_oleh')->nullable()->after('tgl_bayar')->constrained('users')->nullOnDelete();
            $table->dateTime('tgl_verifikasi')->nullable()->after('diverifikasi_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periksa', function (Blueprint $table) {
            $table->dropForeign(['diverifikasi_oleh']);
            $table->dropColumn([
                'status_pembayaran',
                'bukti_pembayaran',
                'tgl_bayar',
                'diverifikasi_oleh',
                'tgl_verifikasi',
            ]);
        });
    }
};
