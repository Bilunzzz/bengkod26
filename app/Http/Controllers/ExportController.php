<?php

namespace App\Http\Controllers;

use App\Exports\ArrayExport;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function adminDokter(): BinaryFileResponse
    {
        $rows = User::query()
            ->with('poli')
            ->where('role', 'dokter')
            ->orderBy('nama')
            ->get()
            ->map(fn (User $user) => [
                $user->id,
                $user->nama,
                $user->email,
                $user->no_hp,
                $user->poli?->nama_poli ?? '-',
            ])
            ->toArray();

        return Excel::download(
            new ArrayExport(['ID', 'Nama Dokter', 'Email', 'No HP', 'Poli'], $rows),
            'data-dokter.xlsx'
        );
    }

    public function adminPasien(): BinaryFileResponse
    {
        $rows = User::query()
            ->where('role', 'pasien')
            ->orderBy('nama')
            ->get()
            ->map(fn (User $user) => [
                $user->id,
                $user->nama,
                $user->email,
                $user->no_rm,
                $user->no_ktp,
                $user->no_hp,
                $user->alamat,
            ])
            ->toArray();

        return Excel::download(
            new ArrayExport(['ID', 'Nama Pasien', 'Email', 'No RM', 'No KTP', 'No HP', 'Alamat'], $rows),
            'data-pasien.xlsx'
        );
    }

    public function adminObat(): BinaryFileResponse
    {
        $rows = Obat::query()
            ->orderBy('nama_obat')
            ->get()
            ->map(fn (Obat $obat) => [
                $obat->id,
                $obat->nama_obat,
                $obat->kemasan,
                $obat->stok,
                $obat->harga,
            ])
            ->toArray();

        return Excel::download(
            new ArrayExport(['ID', 'Nama Obat', 'Kemasan', 'Stok', 'Harga'], $rows),
            'data-obat.xlsx'
        );
    }

    public function dokterJadwal(Request $request): BinaryFileResponse
    {
        $rows = JadwalPeriksa::query()
            ->with('dokter.poli')
            ->where('id_dokter', $request->user()->id)
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 END")
            ->orderBy('jam_mulai')
            ->get()
            ->map(fn (JadwalPeriksa $jadwal) => [
                $jadwal->id,
                $jadwal->dokter?->nama ?? '-',
                $jadwal->dokter?->poli?->nama_poli ?? '-',
                $jadwal->hari,
                substr((string) $jadwal->jam_mulai, 0, 5),
                substr((string) $jadwal->jam_selesai, 0, 5),
            ])
            ->toArray();

        return Excel::download(
            new ArrayExport(['ID Jadwal', 'Dokter', 'Poli', 'Hari', 'Jam Mulai', 'Jam Selesai'], $rows),
            'jadwal-periksa-dokter.xlsx'
        );
    }

    public function dokterRiwayat(Request $request): BinaryFileResponse
    {
        $rows = Periksa::query()
            ->with([
                'daftarPoli.pasien',
                'daftarPoli.jadwalPeriksa.dokter',
            ])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($request) {
                $query->where('id_dokter', $request->user()->id);
            })
            ->latest('tgl_periksa')
            ->get()
            ->map(fn (Periksa $periksa) => [
                $periksa->id,
                $periksa->daftarPoli?->pasien?->nama ?? '-',
                $periksa->daftarPoli?->no_antrian ?? '-',
                $periksa->daftarPoli?->jadwalPeriksa?->hari ?? '-',
                $periksa->tgl_periksa,
                $periksa->biaya_periksa,
                $periksa->catatan,
            ])
            ->toArray();

        return Excel::download(
            new ArrayExport(['ID Periksa', 'Nama Pasien', 'No Antrian', 'Hari Jadwal', 'Tanggal Periksa', 'Biaya Periksa', 'Catatan'], $rows),
            'riwayat-pasien-dokter.xlsx'
        );
    }
}
