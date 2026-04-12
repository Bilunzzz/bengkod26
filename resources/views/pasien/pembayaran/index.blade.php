<x-layouts.app title="Pembayaran">
    <div class="space-y-6">
        @if ($errors->any())
            <div class="alert alert-error rounded-xl text-sm">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-800">Tagihan Pemeriksaan</h2>
                <p class="text-sm text-slate-500">Upload bukti pembayaran untuk tagihan yang belum lunas.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>Poli / Dokter</th>
                            <th>Tanggal Periksa</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihan as $item)
                            <tr>
                                <td>
                                    <p class="font-semibold">{{ $item->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                                </td>
                                <td>{{ optional($item->tgl_periksa)->format('d M Y H:i') }}</td>
                                <td class="font-semibold text-emerald-700">Rp{{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                                <td>
                                    @if ($item->status_pembayaran === 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($item->status_pembayaran === 'menunggu_verifikasi')
                                        <span class="badge badge-warning">Menunggu Verifikasi</span>
                                    @else
                                        <span class="badge badge-neutral">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="link link-primary text-sm">
                                            Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">Belum upload</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_pembayaran !== 'lunas')
                                        <form action="{{ route('pasien.pembayaran.upload', $item) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                            @csrf
                                            <input type="file" name="bukti_pembayaran" class="file-input file-input-bordered file-input-xs w-full max-w-[180px]" accept="image/*" required>
                                            <button type="submit" class="btn btn-xs btn-primary rounded-md px-3 normal-case">Upload</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Belum ada tagihan pemeriksaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.app>
