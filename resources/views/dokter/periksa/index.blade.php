<x-layouts.app title="Pemeriksaan Pasien">
    <div class="space-y-6">
        @if ($errors->any())
            <div class="alert alert-error rounded-xl text-sm">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-800">Antrian Menunggu Pemeriksaan</h2>
                <p class="text-sm text-slate-500">Pilih antrian pasien, isi catatan, lalu tentukan obat.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>Pasien</th>
                            <th>Jadwal</th>
                            <th>No Antrian</th>
                            <th>Keluhan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingQueues as $queue)
                            <tr>
                                <td>{{ $queue->pasien->nama ?? '-' }}</td>
                                <td>{{ $queue->jadwalPeriksa->hari ?? '-' }}, {{ substr((string) $queue->jadwalPeriksa->jam_mulai, 0, 5) }} - {{ substr((string) $queue->jadwalPeriksa->jam_selesai, 0, 5) }}</td>
                                <td class="font-semibold">{{ $queue->no_antrian }}</td>
                                <td>{{ $queue->keluhan }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary rounded-lg px-4 normal-case" onclick="openPeriksaModal('{{ $queue->id }}')">
                                        Periksa
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-slate-500">Belum ada antrian aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800">Daftar Obat</h2>
            <p class="text-sm text-slate-500">Obat dengan stok <= {{ $lowStockLimit }} ditandai peringatan.</p>
            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($obats as $obat)
                    <div class="rounded-xl border p-3 {{ $obat->stok === 0 ? 'border-red-300 bg-red-50' : ($obat->stok <= $lowStockLimit ? 'border-amber-300 bg-amber-50' : 'border-slate-200') }}">
                        <p class="font-semibold text-slate-800">{{ $obat->nama_obat }}</p>
                        <p class="text-xs text-slate-500">{{ $obat->kemasan ?: '-' }}</p>
                        <p class="mt-1 text-sm">Harga: Rp{{ number_format($obat->harga, 0, ',', '.') }}</p>
                        <p class="text-sm font-semibold {{ $obat->stok === 0 ? 'text-red-600' : ($obat->stok <= $lowStockLimit ? 'text-amber-600' : 'text-emerald-600') }}">
                            Stok: {{ $obat->stok }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>

        <dialog id="periksaModal" class="modal">
            <div class="modal-box max-w-3xl">
                <h3 class="text-lg font-bold">Simpan Pemeriksaan</h3>

                <form id="periksaForm" method="POST" action="{{ route('dokter.periksa.store') }}" class="mt-4 space-y-5">
                    @csrf
                    <input type="hidden" name="id_daftar_poli" id="idDaftarPoliInput">

                    <label class="form-control">
                        <span class="label pb-1">
                            <span class="label-text font-semibold text-slate-700">Catatan Dokter</span>
                        </span>
                        <textarea name="catatan" class="textarea textarea-bordered w-full" rows="3" placeholder="Catatan pemeriksaan..."></textarea>
                    </label>

                    <div>
                        <p class="mb-2 text-sm font-semibold text-slate-700">Pilih Obat</p>
                        <div class="max-h-64 overflow-auto rounded-xl border border-slate-200 p-3">
                            @foreach ($obats as $obat)
                                <label class="mb-2 flex items-center justify-between gap-3 rounded-lg border px-3 py-2 transition-all {{ $obat->stok === 0 ? 'cursor-not-allowed border-red-300 bg-red-50' : 'cursor-pointer border-slate-200 bg-white hover:border-indigo-300 hover:bg-indigo-50/40' }}">
                                    <span>
                                        <span class="font-medium">{{ $obat->nama_obat }}</span>
                                        <span class="block text-xs text-slate-500">Rp{{ number_format($obat->harga, 0, ',', '.') }} | Stok: {{ $obat->stok }}</span>
                                    </span>

                                    <span class="relative inline-flex h-6 w-6 items-center justify-center">
                                        <input
                                            type="checkbox"
                                            name="id_obat[]"
                                            value="{{ $obat->id }}"
                                            class="peer absolute inset-0 z-10 m-0 h-full w-full cursor-pointer opacity-0"
                                            @if($obat->stok === 0) disabled @endif
                                        >
                                        <span class="h-6 w-6 rounded-md border-2 transition-all {{ $obat->stok === 0 ? 'border-slate-300 bg-slate-200' : 'border-slate-300 bg-white peer-checked:border-indigo-600 peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-300 peer-focus:ring-offset-1' }}"></span>
                                        <i class="fas fa-check pointer-events-none absolute text-xs text-white opacity-0 transition-opacity {{ $obat->stok === 0 ? '' : 'peer-checked:opacity-100' }}"></i>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                        <button class="btn btn-ghost rounded-lg px-5 normal-case" type="button" onclick="closePeriksaModal()">
                            Batal
                        </button>
                        <button class="btn btn-primary rounded-lg px-5 normal-case" type="submit">
                            Simpan Periksa
                        </button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>

    @push('scripts')
        <script>
            const periksaModal = document.getElementById('periksaModal');
            const idDaftarPoliInput = document.getElementById('idDaftarPoliInput');

            function openPeriksaModal(idDaftarPoli) {
                idDaftarPoliInput.value = idDaftarPoli;
                periksaModal.showModal();
            }

            function closePeriksaModal() {
                periksaModal.close();
            }
        </script>
    @endpush
</x-layouts.app>
