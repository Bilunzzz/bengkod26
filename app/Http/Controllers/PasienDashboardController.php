<?php

namespace App\Http\Controllers;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PasienDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $payload = $this->buildDashboardPayload($request->user()->id);

        return view('pasien.dashboard', [
            'activeQueue' => $payload['active_queue'],
            'jadwalRows' => $payload['jadwal_rows'],
            'hasActiveQueue' => $payload['has_active_queue'],
        ]);
    }

    public function snapshot(Request $request): JsonResponse
    {
        return response()->json($this->buildDashboardPayload($request->user()->id));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_jadwal' => ['required', 'integer', 'exists:jadwal_periksa,id'],
            'keluhan' => ['required', 'string', 'min:5'],
        ]);

        $pasienId = $request->user()->id;

        $activeQueue = DaftarPoli::query()
            ->where('id_pasien', $pasienId)
            ->whereDoesntHave('periksa')
            ->exists();

        if ($activeQueue) {
            return back()->withErrors([
                'queue' => 'Anda masih memiliki antrian aktif. Pendaftaran baru dapat dilakukan setelah pemeriksaan selesai.',
            ]);
        }

        DB::transaction(function () use ($validated, $pasienId) {
            $nextQueueNumber = DaftarPoli::query()
                ->where('id_jadwal', $validated['id_jadwal'])
                ->whereDate('created_at', today())
                ->lockForUpdate()
                ->max('no_antrian');

            DaftarPoli::create([
                'id_jadwal' => $validated['id_jadwal'],
                'id_pasien' => $pasienId,
                'keluhan' => $validated['keluhan'],
                'no_antrian' => ($nextQueueNumber ?? 0) + 1,
            ]);
        });

        return redirect()->route('pasien.dashboard')->with('success', 'Pendaftaran poli berhasil dibuat.');
    }

    private function buildDashboardPayload(int $pasienId): array
    {
        $activeQueue = DaftarPoli::query()
            ->with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', $pasienId)
            ->whereDoesntHave('periksa')
            ->latest()
            ->first();

        $servedByJadwal = Periksa::query()
            ->select('daftar_poli.id_jadwal', DB::raw('MAX(daftar_poli.no_antrian) as current_queue'))
            ->join('daftar_poli', 'periksa.id_daftar_poli', '=', 'daftar_poli.id')
            ->whereDate('periksa.tgl_periksa', today())
            ->where(function ($query) {
                $query
                    ->whereNull('periksa.status_pembayaran')
                    ->orWhere('periksa.status_pembayaran', '!=', 'lunas');
            })
            ->groupBy('daftar_poli.id_jadwal')
            ->pluck('current_queue', 'daftar_poli.id_jadwal');

        $patientActiveByJadwal = DaftarPoli::query()
            ->where('id_pasien', $pasienId)
            ->whereDoesntHave('periksa')
            ->pluck('no_antrian', 'id_jadwal');

        $jadwalRows = JadwalPeriksa::query()
            ->with(['dokter.poli'])
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 END")
            ->orderBy('jam_mulai')
            ->get()
            ->map(function (JadwalPeriksa $jadwal) use ($servedByJadwal, $patientActiveByJadwal) {
                return [
                    'id' => $jadwal->id,
                    'poli' => $jadwal->dokter?->poli?->nama_poli ?? '-',
                    'dokter' => $jadwal->dokter?->nama ?? '-',
                    'hari' => $jadwal->hari,
                    'jam' => substr((string) $jadwal->jam_mulai, 0, 5) . ' - ' . substr((string) $jadwal->jam_selesai, 0, 5),
                    'current_queue' => $servedByJadwal->get($jadwal->id),
                    'your_queue' => $patientActiveByJadwal->get($jadwal->id),
                ];
            });

        return [
            'has_active_queue' => $activeQueue !== null,
            'active_queue' => $this->transformActiveQueue($activeQueue, $servedByJadwal),
            'jadwal_rows' => $jadwalRows,
        ];
    }

    private function transformActiveQueue(?DaftarPoli $activeQueue, Collection $servedByJadwal): ?array
    {
        if (!$activeQueue) {
            return null;
        }

        $jadwal = $activeQueue->jadwalPeriksa;

        return [
            'id_jadwal' => $jadwal?->id,
            'poli' => $jadwal?->dokter?->poli?->nama_poli ?? '-',
            'dokter' => $jadwal?->dokter?->nama ?? '-',
            'jadwal' => $jadwal
                ? $jadwal->hari . ', ' . substr((string) $jadwal->jam_mulai, 0, 5) . ' - ' . substr((string) $jadwal->jam_selesai, 0, 5)
                : '-',
            'nomor_antrian_pasien' => $activeQueue->no_antrian,
            'nomor_dilayani' => $jadwal ? $servedByJadwal->get($jadwal->id) : null,
        ];
    }
}
