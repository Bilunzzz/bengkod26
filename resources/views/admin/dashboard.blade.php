<x-layouts.app title="Admin Dashboard">
    <div class="space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800">Halo, Selamat Datang Admin</h1>
            <p class="mt-1 text-sm text-slate-500">Gunakan tombol di bawah untuk export data Excel.</p>

            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('admin.export.dokter') }}" class="btn btn-primary btn-sm rounded-lg px-4 normal-case">
                    <i class="fas fa-file-excel"></i>
                    Export Data Dokter
                </a>
                <a href="{{ route('admin.export.pasien') }}" class="btn btn-secondary btn-sm rounded-lg px-4 normal-case">
                    <i class="fas fa-file-excel"></i>
                    Export Data Pasien
                </a>
                <a href="{{ route('admin.export.obat') }}" class="btn btn-accent btn-sm rounded-lg px-4 normal-case">
                    <i class="fas fa-file-excel"></i>
                    Export Data Obat
                </a>
            </div>
        </section>
    </div>
</x-layouts.app>