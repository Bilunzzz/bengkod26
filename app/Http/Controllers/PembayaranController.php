<?php

namespace App\Http\Controllers;

use App\Models\Periksa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function pasienIndex(Request $request): View
    {
        $tagihan = Periksa::query()
            ->with(['daftarPoli.jadwalPeriksa.dokter.poli'])
            ->whereHas('daftarPoli', function ($query) use ($request) {
                $query->where('id_pasien', $request->user()->id);
            })
            ->latest('tgl_periksa')
            ->get();

        return view('pasien.pembayaran.index', [
            'tagihan' => $tagihan,
        ]);
    }

    public function upload(Request $request, Periksa $periksa): RedirectResponse
    {
        $isOwner = $periksa->daftarPoli()->where('id_pasien', $request->user()->id)->exists();

        if (!$isOwner) {
            abort(403);
        }

        if ($periksa->status_pembayaran === 'lunas') {
            return back()->withErrors([
                'payment' => 'Tagihan ini sudah lunas dan tidak bisa diubah.',
            ]);
        }

        $validated = $request->validate([
            'bukti_pembayaran' => ['required', 'image', 'max:2048'],
        ]);

        $path = $validated['bukti_pembayaran']->store('bukti-pembayaran', 'public');

        $periksa->update([
            'bukti_pembayaran' => $path,
            'tgl_bayar' => now(),
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function adminIndex(): View
    {
        $menungguVerifikasi = Periksa::query()
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli'])
            ->where('status_pembayaran', 'menunggu_verifikasi')
            ->latest('tgl_bayar')
            ->get();

        $riwayatVerifikasi = Periksa::query()
            ->with(['daftarPoli.pasien', 'verifier'])
            ->where('status_pembayaran', 'lunas')
            ->latest('tgl_verifikasi')
            ->limit(20)
            ->get();

        return view('admin.pembayaran.index', [
            'menungguVerifikasi' => $menungguVerifikasi,
            'riwayatVerifikasi' => $riwayatVerifikasi,
        ]);
    }

    public function verify(Request $request, Periksa $periksa): RedirectResponse
    {
        if ($periksa->status_pembayaran !== 'menunggu_verifikasi') {
            return back()->withErrors([
                'payment' => 'Tagihan ini tidak dalam status menunggu verifikasi.',
            ]);
        }

        $periksa->update([
            'status_pembayaran' => 'lunas',
            'diverifikasi_oleh' => $request->user()->id,
            'tgl_verifikasi' => now(),
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi. Status tagihan menjadi lunas.');
    }
}
