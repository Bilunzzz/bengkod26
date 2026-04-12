<x-layouts.app title="Detail Riwayat Pemeriksaan">
    <div class="space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Ringkasan Pemeriksaan</h2>
                    <p class="text-sm text-slate-500">Detail hasil pemeriksaan berdasarkan riwayat pendaftaran poli.</p>
                </div>
                <a href="{{ route('pasien.riwayat.index') }}" class="btn btn-sm rounded-lg px-4 normal-case">Kembali</a>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-widest text-slate-500">Poli</p>
                    <p class="mt-1 font-semibold">{{ $daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>

                    <p class="mt-4 text-xs uppercase tracking-widest text-slate-500">Dokter</p>
                    <p class="mt-1 font-semibold">{{ $daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</p>

                    <p class="mt-4 text-xs uppercase tracking-widest text-slate-500">Jadwal</p>
                    <p class="mt-1 font-semibold">
                        {{ $daftarPoli->jadwalPeriksa->hari ?? '-' }},
                        {{ substr((string) ($daftarPoli->jadwalPeriksa->jam_mulai ?? '00:00'), 0, 5) }} - {{ substr((string) ($daftarPoli->jadwalPeriksa->jam_selesai ?? '00:00'), 0, 5) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <p class="text-xs uppercase tracking-widest text-slate-500">Nomor Antrian</p>
                    <p class="mt-1 text-xl font-black text-indigo-700">{{ $daftarPoli->no_antrian }}</p>

                    <p class="mt-4 text-xs uppercase tracking-widest text-slate-500">Tanggal Periksa</p>
                    <p class="mt-1 font-semibold">{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d M Y H:i') }}</p>

                    <p class="mt-4 text-xs uppercase tracking-widest text-slate-500">Total Biaya</p>
                    <p class="mt-1 text-xl font-black text-emerald-700">Rp{{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-bold text-slate-800">Catatan Dokter</h3>
            <p class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                {{ $periksa->catatan ?: 'Tidak ada catatan.' }}
            </p>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-bold text-slate-800">Daftar Obat</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($obatSummary as $index => $obat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $obat['nama_obat'] }}</td>
                                <td>{{ $obat['jumlah'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-slate-500">Tidak ada obat pada pemeriksaan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.app>
