<x-layouts.app title="Riwayat Pendaftaran Poli">
    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-lg font-bold text-slate-800">Riwayat Pendaftaran</h2>
            <p class="text-sm text-slate-500">Menampilkan riwayat pendaftaran Anda dari yang terbaru.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-slate-500">
                        <th>No</th>
                        <th>Nama Poli</th>
                        <th>Dokter</th>
                        <th>Tanggal Jadwal</th>
                        <th>No Antrian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $index => $item)
                        <tr>
                            <td>{{ $riwayat->firstItem() + $index }}</td>
                            <td>{{ $item->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</td>
                            <td>{{ $item->jadwalPeriksa->dokter->nama ?? '-' }}</td>
                            <td>
                                {{ $item->jadwalPeriksa->hari ?? '-' }},
                                {{ substr((string) ($item->jadwalPeriksa->jam_mulai ?? '00:00'), 0, 5) }}
                                -
                                {{ substr((string) ($item->jadwalPeriksa->jam_selesai ?? '00:00'), 0, 5) }}
                            </td>
                            <td class="font-semibold">{{ $item->no_antrian }}</td>
                            <td>
                                @if($item->periksa)
                                    <span class="badge badge-success badge-sm">Sudah Diperiksa</span>
                                @else
                                    <span class="badge badge-warning badge-sm">Menunggu Pemeriksaan</span>
                                @endif
                            </td>
                            <td>
                                @if($item->periksa)
                                    <a href="{{ route('pasien.riwayat.show', $item) }}" class="btn btn-sm btn-primary rounded-lg px-4 normal-case">Detail</a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-500">Belum ada riwayat pendaftaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-5 py-4">
            {{ $riwayat->links() }}
        </div>
    </section>
</x-layouts.app>
