<x-layouts.app title="Verifikasi Pembayaran">
    <div class="space-y-6">
        @if ($errors->any())
            <div class="alert alert-error rounded-xl text-sm">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-800">Tagihan Menunggu Verifikasi</h2>
                <p class="text-sm text-slate-500">Periksa bukti pembayaran lalu konfirmasi untuk mengubah status menjadi lunas.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>Pasien</th>
                            <th>Poli / Dokter</th>
                            <th>Total Tagihan</th>
                            <th>Upload Pada</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menungguVerifikasi as $item)
                            <tr>
                                <td>{{ $item->daftarPoli->pasien->nama ?? '-' }}</td>
                                <td>
                                    <p class="font-semibold">{{ $item->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                                </td>
                                <td class="font-semibold text-emerald-700">Rp{{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                                <td>{{ optional($item->tgl_bayar)->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="btn btn-xs btn-outline btn-info rounded-md px-3 normal-case">
                                        Lihat Bukti
                                    </a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.pembayaran.verify', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-success rounded-md px-3 normal-case">
                                            Konfirmasi Lunas
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Tidak ada tagihan yang menunggu verifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-800">Riwayat Verifikasi Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>Pasien</th>
                            <th>Total Tagihan</th>
                            <th>Diverifikasi Oleh</th>
                            <th>Tanggal Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatVerifikasi as $item)
                            <tr>
                                <td>{{ $item->daftarPoli->pasien->nama ?? '-' }}</td>
                                <td>Rp{{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                                <td>{{ $item->verifier->nama ?? '-' }}</td>
                                <td>{{ optional($item->tgl_verifikasi)->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500">Belum ada riwayat verifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.app>
