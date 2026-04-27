<x-layouts.app title="Manajemen Dokter">
    <div class="space-y-6">
        @if ($errors->any())
            <div class="alert alert-error rounded-xl text-sm">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800">Tambah Dokter</h2>
            <form action="{{ route('admin.dokter.store') }}" method="POST" class="mt-4 grid gap-3 md:grid-cols-3">
                @csrf
                <input type="text" name="nama" class="input input-bordered" placeholder="Nama dokter" required>
                <input type="email" name="email" class="input input-bordered" placeholder="Email" required>
                <select name="id_poli" class="select select-bordered" required>
                    <option value="" selected disabled>Pilih poli</option>
                    @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                    @endforeach
                </select>
                <input type="text" name="alamat" class="input input-bordered" placeholder="Alamat (opsional)">
                <input type="text" name="no_hp" class="input input-bordered" placeholder="No HP (opsional)">
                <input type="text" name="no_ktp" class="input input-bordered" placeholder="No KTP (opsional)">
                <input type="password" name="password" class="input input-bordered" placeholder="Password" required>
                <input type="password" name="password_confirmation" class="input input-bordered" placeholder="Konfirmasi password" required>
                <div>
                    <button type="submit" class="btn btn-primary btn-sm rounded-lg px-4 normal-case">Tambah</button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-lg font-bold text-slate-800">Daftar Dokter</h2>
                <p class="text-sm text-slate-500">Kosongkan password jika tidak ingin mengubah password.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-500">
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Poli</th>
                            <th>No HP</th>
                            <th>No KTP</th>
                            <th>Alamat</th>
                            <th>Password Baru</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dokters as $dokter)
                            <tr>
                                <td>
                                    <input type="text" name="nama" value="{{ $dokter->nama }}" class="input input-bordered input-sm w-40" form="update-dokter-{{ $dokter->id }}" required>
                                </td>
                                <td>
                                    <input type="email" name="email" value="{{ $dokter->email }}" class="input input-bordered input-sm w-52" form="update-dokter-{{ $dokter->id }}" required>
                                </td>
                                <td>
                                    <select name="id_poli" class="select select-bordered select-sm w-40" form="update-dokter-{{ $dokter->id }}" required>
                                        @foreach ($polis as $poli)
                                            <option value="{{ $poli->id }}" @selected($dokter->id_poli == $poli->id)>
                                                {{ $poli->nama_poli }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="no_hp" value="{{ $dokter->no_hp }}" class="input input-bordered input-sm w-36" form="update-dokter-{{ $dokter->id }}">
                                </td>
                                <td>
                                    <input type="text" name="no_ktp" value="{{ $dokter->no_ktp }}" class="input input-bordered input-sm w-40" form="update-dokter-{{ $dokter->id }}">
                                </td>
                                <td>
                                    <input type="text" name="alamat" value="{{ $dokter->alamat }}" class="input input-bordered input-sm w-48" form="update-dokter-{{ $dokter->id }}">
                                </td>
                                <td>
                                    <div class="space-y-2">
                                        <input type="password" name="password" class="input input-bordered input-sm w-40" placeholder="Password baru" form="update-dokter-{{ $dokter->id }}">
                                        <input type="password" name="password_confirmation" class="input input-bordered input-sm w-40" placeholder="Konfirmasi" form="update-dokter-{{ $dokter->id }}">
                                    </div>
                                </td>
                                <td>
                                    <form id="update-dokter-{{ $dokter->id }}" action="{{ route('admin.dokter.update', $dokter) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex flex-col gap-2">
                                            <button class="btn btn-sm btn-secondary rounded-lg px-4 normal-case" type="submit">Simpan</button>
                                            <button class="btn btn-sm btn-error rounded-lg px-4 normal-case" type="submit" form="delete-dokter-{{ $dokter->id }}" onclick="return confirm('Hapus dokter ini?')">Hapus</button>
                                        </div>
                                    </form>
                                    <form id="delete-dokter-{{ $dokter->id }}" action="{{ route('admin.dokter.destroy', $dokter) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-slate-500">Belum ada data dokter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.app>
