<x-layouts.app title="Manajemen Poli">
    <div class="space-y-6">
        @if ($errors->any())
            <div class="alert alert-error rounded-xl text-sm">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800">Tambah Poli</h2>
            <form action="{{ route('admin.poli.store') }}" method="POST" class="mt-4 grid gap-3 md:grid-cols-3">
                @csrf
                <input type="text" name="nama_poli" class="input input-bordered" placeholder="Nama poli" maxlength="25" required>
                <input type="text" name="keterangan" class="input input-bordered md:col-span-2" placeholder="Keterangan (opsional)">
                <div>
                    <button type="submit" class="btn btn-primary btn-sm rounded-lg px-4 normal-case">Tambah</button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-800">Daftar Poli</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>Nama Poli</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($polis as $poli)
                            <tr>
                                <td>
                                    <input type="text" name="nama_poli" value="{{ $poli->nama_poli }}" class="input input-bordered input-sm w-full" maxlength="25" form="update-poli-{{ $poli->id }}" required>
                                </td>
                                <td>
                                    <input type="text" name="keterangan" value="{{ $poli->keterangan }}" class="input input-bordered input-sm w-full" form="update-poli-{{ $poli->id }}">
                                </td>
                                <td>
                                    <form id="update-poli-{{ $poli->id }}" action="{{ route('admin.poli.update', $poli) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center gap-2">
                                            <button class="btn btn-sm btn-secondary rounded-lg px-4 normal-case" type="submit">Simpan</button>
                                            <button class="btn btn-sm btn-error rounded-lg px-4 normal-case" type="submit" form="delete-poli-{{ $poli->id }}" onclick="return confirm('Hapus poli ini?')">Hapus</button>
                                        </div>
                                    </form>
                                    <form id="delete-poli-{{ $poli->id }}" action="{{ route('admin.poli.destroy', $poli) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-slate-500">Belum ada data poli.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.app>
