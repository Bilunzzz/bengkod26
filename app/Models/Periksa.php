<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Periksa extends Model
{
    protected $table = 'periksa';

    protected $fillable = [
        'id_daftar_poli',
        'tgl_periksa',
        'catatan',
        'biaya_periksa',
        'status_pembayaran',
        'bukti_pembayaran',
        'tgl_bayar',
        'diverifikasi_oleh',
        'tgl_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tgl_periksa' => 'datetime',
            'tgl_bayar' => 'datetime',
            'tgl_verifikasi' => 'datetime',
        ];
    }

    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
