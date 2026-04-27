<x-layouts.app title="Manajemen Obat">
    <div class="space-y-6">
        @if ($errors->any())
            <div class="alert alert-error rounded-xl text-sm">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-slate-800">Tambah Obat</h2>
                <a href="{{ route('admin.export.obat') }}" class="btn btn-sm btn-success rounded-lg px-4 normal-case">
                    <i class="fas fa-file-excel"></i>
                    Export Excel Obat
                </a>
            </div>
            <form action="{{ route('admin.obat.store') }}" method="POST" class="mt-4 grid gap-3 md:grid-cols-4">
                @csrf
                <input type="text" name="nama_obat" class="input input-bordered" placeholder="Nama obat" required>
                <input type="text" name="kemasan" class="input input-bordered" placeholder="Kemasan (opsional)">
                <input type="number" name="harga" class="input input-bordered" placeholder="Harga" min="0" required>
                <div class="flex gap-2">
                    <input type="number" name="stok" class="input input-bordered w-full" placeholder="Stok" min="0" required>
                    <button type="submit" class="btn btn-primary btn-sm rounded-lg px-4 normal-case">Tambah</button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-800">Daftar Obat</h2>
                <p class="text-sm text-slate-500">Obat dengan stok <= {{ $lowStockLimit }} ditandai peringatan.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>Nama Obat</th>
                            <th>Kemasan</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($obats as $obat)
                            <tr class="{{ $obat->stok <= $lowStockLimit ? 'bg-amber-50' : '' }}">
                                <td>
                                    <input type="text" name="nama_obat" value="{{ $obat->nama_obat }}" class="input input-bordered input-sm w-full" form="update-obat-{{ $obat->id }}" required>
                                </td>
                                <td>
                                    <input type="text" name="kemasan" value="{{ $obat->kemasan }}" class="input input-bordered input-sm w-full" form="update-obat-{{ $obat->id }}">
                                </td>
                                <td>
                                    <input type="number" name="harga" value="{{ $obat->harga }}" class="input input-bordered input-sm w-full" min="0" form="update-obat-{{ $obat->id }}" required>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="stok" value="{{ $obat->stok }}" class="input input-bordered input-sm w-24" min="0" form="update-obat-{{ $obat->id }}" required>
                                        @if ($obat->stok <= $lowStockLimit)
                                            <span class="badge badge-warning badge-sm">Rendah</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <form id="update-obat-{{ $obat->id }}" action="{{ route('admin.obat.update', $obat) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center gap-2">
                                            <button class="btn btn-sm btn-secondary rounded-lg px-4 normal-case" type="submit">Simpan</button>
                                            <button class="btn btn-sm btn-error rounded-lg px-4 normal-case" type="submit" form="delete-obat-{{ $obat->id }}" onclick="return confirm('Hapus obat ini?')">Hapus</button>
                                        </div>
                                    </form>
                                    <form id="delete-obat-{{ $obat->id }}" action="{{ route('admin.obat.destroy', $obat) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-slate-500">Belum ada data obat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.app>
