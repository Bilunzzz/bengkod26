<?php

namespace App\Http\Controllers;

use App\Events\QueueUpdated;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class DokterPeriksaController extends Controller
{
    private const LOW_STOCK_LIMIT = 5;
    private const CONSULTATION_FEE = 150000;

    public function index(Request $request): View
    {
        $dokterId = $request->user()->id;

        $pendingQueues = DaftarPoli::query()
            ->with(['pasien', 'jadwalPeriksa'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->whereDoesntHave('periksa')
            ->orderByDesc('created_at')
            ->get();

        $obats = Obat::query()->orderBy('nama_obat')->get();

        return view('dokter.periksa.index', [
            'pendingQueues' => $pendingQueues,
            'obats' => $obats,
            'lowStockLimit' => self::LOW_STOCK_LIMIT,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_daftar_poli' => ['required', 'integer', 'exists:daftar_poli,id'],
            'catatan' => ['nullable', 'string'],
            'id_obat' => ['required', 'array', 'min:1'],
            'id_obat.*' => ['required', 'integer', 'exists:obat,id'],
        ]);

        $dokterId = $request->user()->id;

        $daftarPoli = DaftarPoli::query()
            ->whereKey($validated['id_daftar_poli'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->whereDoesntHave('periksa')
            ->first();

        if (!$daftarPoli) {
            throw ValidationException::withMessages([
                'id_daftar_poli' => 'Antrian tidak valid atau sudah diperiksa.',
            ]);
        }

        $obatCounts = collect($validated['id_obat'])
            ->countBy()
            ->map(fn ($qty) => (int) $qty);

        DB::transaction(function () use ($daftarPoli, $obatCounts, $validated) {
            $obats = Obat::query()
                ->whereIn('id', $obatCounts->keys()->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($obatCounts as $obatId => $qty) {
                $obat = $obats->get((int) $obatId);

                if (!$obat || $obat->stok < $qty) {
                    $namaObat = $obat?->nama_obat ?? 'Obat tidak ditemukan';
                    $stokTersedia = $obat?->stok ?? 0;

                    throw ValidationException::withMessages([
                        'id_obat' => "Stok {$namaObat} tidak mencukupi (tersedia: {$stokTersedia}, diminta: {$qty}). Proses dibatalkan.",
                    ]);
                }
            }

            $totalHargaObat = 0;
            foreach ($obatCounts as $obatId => $qty) {
                $obat = $obats->get((int) $obatId);
                $obat->decrement('stok', $qty);
                $totalHargaObat += ($obat->harga * $qty);
            }

            $periksa = Periksa::query()->create([
                'id_daftar_poli' => $daftarPoli->id,
                'tgl_periksa' => now(),
                'catatan' => $validated['catatan'] ?? null,
                'biaya_periksa' => self::CONSULTATION_FEE + $totalHargaObat,
            ]);

            foreach ($obatCounts as $obatId => $qty) {
                for ($i = 0; $i < $qty; $i++) {
                    DetailPeriksa::query()->create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => (int) $obatId,
                    ]);
                }
            }
        });

        try {
            event(new QueueUpdated((int) $daftarPoli->id_jadwal, (int) $daftarPoli->no_antrian));
        } catch (Throwable $exception) {
            Log::warning('QueueUpdated broadcast failed', [
                'jadwal_id' => (int) $daftarPoli->id_jadwal,
                'queue_number' => (int) $daftarPoli->no_antrian,
                'error' => $exception->getMessage(),
            ]);
        }

        return back()->with('success', 'Pemeriksaan berhasil disimpan dan stok obat diperbarui.');
    }
}
