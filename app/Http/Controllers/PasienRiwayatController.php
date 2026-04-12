<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasienRiwayatController extends Controller
{
    public function index(Request $request): View
    {
        $riwayat = DaftarPoli::query()
            ->with([
                'jadwalPeriksa.dokter.poli',
                'periksa:id,id_daftar_poli,tgl_periksa,biaya_periksa',
            ])
            ->where('id_pasien', $request->user()->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pasien.riwayat.index', [
            'riwayat' => $riwayat,
        ]);
    }

    public function show(Request $request, DaftarPoli $daftarPoli): View
    {
        if ($daftarPoli->id_pasien !== $request->user()->id) {
            abort(403);
        }

        $daftarPoli->load([
            'jadwalPeriksa.dokter.poli',
            'periksa.detailPeriksas.obat',
        ]);

        if (!$daftarPoli->periksa) {
            abort(404);
        }

        $obatSummary = $daftarPoli->periksa->detailPeriksas
            ->groupBy(fn ($detail) => $detail->obat?->nama_obat ?? 'Obat tidak ditemukan')
            ->map(fn ($rows, $namaObat) => [
                'nama_obat' => $namaObat,
                'jumlah' => $rows->count(),
            ])
            ->values();

        return view('pasien.riwayat.show', [
            'daftarPoli' => $daftarPoli,
            'periksa' => $daftarPoli->periksa,
            'obatSummary' => $obatSummary,
        ]);
    }
}
